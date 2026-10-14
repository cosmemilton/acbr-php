<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

use Acbr\AcbrException;
use Acbr\Async\AsyncEmitter;
use Acbr\Async\OperationQueue;
use Acbr\Configuration;
use Acbr\CertificateA1;
use Acbr\Document;
use Acbr\Emitter;
use Acbr\Engine;
use Acbr\FiscalResult;
use Acbr\ProcessTransport;

if (PHP_OS_FAMILY !== 'Linux' || !function_exists('pcntl_fork') || !function_exists('posix_kill')) {
    echo "Async: skipped (optional Linux pcntl/posix backend unavailable)\n";
    exit(0);
}
$count = 0;
$check = static function (bool $condition, string $name) use (&$count): void {
    if (!$condition) { throw new RuntimeException('FAILED: ' . $name); }
    $count++;
};
$expect = static function (callable $call, string $code) use ($check): AcbrException {
    try { $call(); throw new RuntimeException('Expected ' . $code); }
    catch (AcbrException $error) { $check($error->codigo === $code, $code . ' code'); return $error; }
};
$result = static fn(): FiscalResult => new FiscalResult(['sucesso'=>true, 'estado'=>'VALIDADO', 'xmlBase64'=>base64_encode('<private-xml/>')], 'validar');
$directory = sys_get_temp_dir() . '/acbr-php-async-' . bin2hex(random_bytes(8));
mkdir($directory, 0700);
$queues = [];
try {
    // A result really runs in a different process before await/tick is called.
    $queue = $queues[] = new OperationQueue(2);
    $start = hrtime(true);
    $future = $queue->submit(static function (callable $cancelled) use ($directory, $result): FiscalResult {
        file_put_contents($directory . '/started', (string)getmypid());
        usleep(300000);
        return $result();
    }, false);
    $check((hrtime(true) - $start) / 1e9 < 0.15, 'submit is asynchronous');
    usleep(40000);
    $check(is_file($directory . '/started'), 'operation starts without await');
    $workerPid = (int)file_get_contents($directory . '/started');
    $check($workerPid !== getmypid(), 'operation runs in child');
    $expect(static fn() => $future->result(), 'RESULTADO_PENDENTE');
    $check($future->await()->estado() === 'VALIDADO', 'future resolves');
    $check($future->result()->xml() === '<private-xml/>', 'explicit XML survives IPC');
    $check($future->result()->comando() === 'validar' && $future->result()->statusEnvio() === 'nao_enviado', 'result operation metadata survives IPC');
    $check(!$future->cancel() && $future->state() === 'fulfilled', 'settled future is stable');
    $check(!posix_kill($workerPid, 0), 'completed child is reaped');
    $queue->close();
    $expect(static fn() => $queue->submit($result), 'FILA_FECHADA');

    // Capacity includes running + queued, FIFO and no overlapping operations.
    $queue = $queues[] = new OperationQueue(3);
    $make = static function (int $number) use ($directory, $result): Closure {
        return static function (callable $cancelled) use ($directory, $result, $number): FiscalResult {
            file_put_contents($directory . '/order', 'start' . $number . "\n", FILE_APPEND | LOCK_EX);
            usleep(60000);
            file_put_contents($directory . '/order', 'end' . $number . "\n", FILE_APPEND | LOCK_EX);
            return $result();
        };
    };
    $first = $queue->submit($make(1), false);
    $second = $queue->submit($make(2), false);
    $third = $queue->submit($make(3), false);
    $check($second->state() === 'pending', 'pending state');
    $expect(static fn() => $queue->submit($make(4)), 'FILA_CHEIA');
    $check($second->cancel(), 'queued cancellation accepted');
    $cancel = $expect(static fn() => $second->await(), 'OPERACAO_CANCELADA');
    $check(!$cancel->resultadoDesconhecido && $cancel->limpezaConfirmada, 'queued cancellation is known not executed');
    $fourth = $queue->submit($make(4), false);
    $queue->drain();
    $check(file_get_contents($directory . '/order') === "start1\nend1\nstart3\nend3\nstart4\nend4\n", 'serial FIFO and no cancelled execution');
    $check($first->isComplete() && $third->isComplete() && $fourth->isComplete(), 'drain settles accepted futures');
    $queue->close();

    // Cooperative timeout/cancellation and continued service with no retries.
    $queue = $queues[] = new OperationQueue(2, 70);
    $slow = static function (callable $cancelled) use ($directory): FiscalResult {
        file_put_contents($directory . '/attempts', 'x', FILE_APPEND);
        while (!$cancelled()) { usleep(1000); }
        throw new AcbrException('OPERACAO_CANCELADA', 'private exception must not cross IPC', true, true, base64_encode('<recovery-private/>'));
    };
    $timed = $queue->submit($slow);
    $timeout = $expect(static fn() => $timed->await(), 'TIMEOUT_MOTOR');
    $check($timeout->resultadoDesconhecido && $timeout->limpezaConfirmada, 'timeout uncertainty and cleanup');
    $check($timeout->xmlRecuperacao() === '<recovery-private/>', 'recovery XML preserved across timeout wrapping');
    $check(!str_contains(json_encode($timeout) . print_r($timeout, true), 'recovery-private'), 'recovery XML remains redacted');
    $check(file_get_contents($directory . '/attempts') === 'x', 'no automatic retry');
    $check($queue->submit($result, false)->await()->sucesso(), 'queue usable after confirmed cleanup');
    $queue->close();

    $queue = $queues[] = new OperationQueue(2);
    $running = $queue->submit($slow);
    usleep(10000);
    $check($running->cancel(), 'running cancellation accepted');
    $cancel = $expect(static fn() => $running->await(), 'OPERACAO_CANCELADA');
    $check($cancel->resultadoDesconhecido && $cancel->limpezaConfirmada, 'running cancellation conservative');
    $check($cancel->xmlRecuperacao() === '<recovery-private/>', 'recovery survives cancellation');
    $queue->close();

    // Close rejects queued work before running it and cleans the active child.
    $queue = $queues[] = new OperationQueue(2);
    $running = $queue->submit($slow);
    $pending = $queue->submit($make(9));
    $queue->close();
    $expect(static fn() => $running->await(), 'FILA_FECHADA');
    $closed = $expect(static fn() => $pending->await(), 'FILA_FECHADA');
    $check(!$closed->resultadoDesconhecido, 'closed pending operation never ran');
    $check(!str_contains(file_get_contents($directory . '/order'), '9'), 'close never launches queued work');
    $queue->close();

    // A callback that ignores the probe still has a bounded owned-group stop.
    $queue = $queues[] = new OperationQueue(1, 40, cancellationGraceMs:30);
    $stuck = $queue->submit(static function () use ($directory, $result): FiscalResult {
        file_put_contents($directory . '/stuck-pid', (string)getmypid());
        sleep(60);
        return $result();
    });
    $start = hrtime(true);
    $timeout = $expect(static fn() => $stuck->await(), 'TIMEOUT_MOTOR');
    $check((hrtime(true) - $start) / 1e9 < 1.5, 'forced timeout is bounded');
    $check($timeout->limpezaConfirmada && !posix_kill((int)file_get_contents($directory . '/stuck-pid'), 0), 'forced child was reaped');
    $queue->close();

    // Unexpected child exit produces uncertainty and no retry.
    $queue = $queues[] = new OperationQueue();
    $crashed = $queue->submit(static function (): never { posix_kill(getmypid(), SIGKILL); exit(1); });
    $crash = $expect(static fn() => $crashed->await(), 'FALHA_ASYNC');
    $check($crash->resultadoDesconhecido && $crash->limpezaConfirmada, 'unexpected worker crash conservative');
    $queue->close();

    // Child-reported uncertain cleanup must poison the parent queue as well.
    $queue = $queues[] = new OperationQueue(2);
    $failed = $queue->submit(static function (): never { usleep(30000); throw new AcbrException('FALHA_MOTOR', 'private', true, false); });
    $pending = $queue->submit($result);
    $failure = $expect(static fn() => $failed->await(), 'FALHA_MOTOR');
    $check(!$failure->limpezaConfirmada, 'child cleanup failure propagated');
    $expect(static fn() => $pending->await(), 'FILA_FECHADA');
    $expect(static fn() => $queue->submit($result), 'FILA_FECHADA');
    $expect(static fn() => $queue->close(), 'FALHA_LIMPEZA_ASYNC');

    // Bounded IPC and delayed polling preserve completed large results.
    $queue = $queues[] = new OperationQueue(1, 50);
    $large = $queue->submit(static fn() => new FiscalResult(['sucesso'=>true, 'estado'=>'VALIDADO', 'xmlBase64'=>base64_encode(str_repeat('z', 600000))], 'validar'), false);
    usleep(100000);
    $check(strlen($large->await()->xml()) === 600000, 'completed result survives delayed await after deadline');
    $queue->close();
    $queue = $queues[] = new OperationQueue(1, 1000, 256);
    $large = $queue->submit(static fn() => new FiscalResult(['sucesso'=>true, 'estado'=>'VALIDADO', 'xmlBase64'=>base64_encode(str_repeat('z', 500))]), false);
    $expect(static fn() => $large->await(), 'RESPOSTA_EXCESSIVA');
    $queue->close();

    // Arbitrary exception messages, stacks and closure captures never cross IPC.
    ini_set('zend.exception_ignore_args', '0');
    $queue = $queues[] = new OperationQueue();
    $secret = 'ASYNC_PRIVATE_SENTINEL';
    $bad = $queue->submit(static function () use ($secret): never { throw new RuntimeException($secret); });
    $failure = $expect(static fn() => $bad->await(), 'FALHA_ASYNC');
    $check(!str_contains(json_encode($failure) . print_r($failure, true) . print_r($failure->getTrace(), true) . print_r($queue, true) . print_r($bad, true), $secret), 'exception and debug redaction');
    $invalid = $queue->submit(static fn(): string => 'wrong-private-type', false);
    $expect(static fn() => $invalid->await(), 'RESULTADO_ASYNC_INVALIDO');
    $queue->close();

    // Synchronous Emitter and typed Documents also work through the facade.
    $engine = new class implements Engine {
        public function execute(#[SensitiveParameter] array $request, ?callable $cancelled = null): FiscalResult
        {
            return new FiscalResult(['sucesso'=>true, 'estado'=>$request['comando'] === 'gerarXml' ? 'XML_GERADO' : 'VALIDADO', 'xmlBase64'=>base64_encode('<mock/>')], $request['comando']);
        }
    };
    $emitter = new Emitter(new Configuration('00000000000000', 'SP'), engine:$engine);
    $async = new AsyncEmitter($emitter);
    $document = $async->gerarXml(new Document());
    $check($document->await()->estado() === 'XML_GERADO', 'facade accepts immutable Document');
    $check($async->gerarXml([])->await()->estado() === 'XML_GERADO', 'facade accepts array');
    $check($async->validar('<mock/>')->await()->statusEnvio() === 'nao_enviado', 'facade returns operation-aware result');
    $expect(static fn() => $async->assinar('<mock/>')->await(), 'CERTIFICADO_AUSENTE');
    $check(!str_contains(print_r($async, true), '00000000000000'), 'facade debug redacts configuration');
    $async->fechar();
    $expect(static fn() => $async->validar('<mock/>'), 'FILA_FECHADA');

    // Facade freezes valid fiscal data when scheduled, including references.
    $engine = new class($directory) implements Engine {
        public function __construct(private string $directory) {}
        public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
        {
            if ($request['comando'] === 'validar') { usleep(60000); }
            if ($request['comando'] === 'gerarXml') { file_put_contents($this->directory . '/snapshot-document', json_decode(json_encode($request['documento']), true)['Emit']['xNome'] ?? 'missing'); }
            if ($request['comando'] === 'consultar') { file_put_contents($this->directory . '/snapshot-key', $request['chave']); }
            return new FiscalResult(['sucesso'=>true, 'estado'=>'VALIDADO'], $request['comando']);
        }
    };
    $async = new AsyncEmitter(new Emitter(new Configuration('00000000000000', 'SP', certificado:CertificateA1::fromData('mock-only', 'mock-only')), engine:$engine));
    $barrier = $async->validar('<mock/>');
    $mutable = (object)['xNome'=>'ORIGINAL'];
    $frozenDocument = $async->gerarXml(['Emit'=>$mutable]);
    $key = str_repeat('1',44);
    $frozenKey = $async->consultar(['chave'=>&$key]);
    $mutable->xNome = 'MUTATED'; $key = str_repeat('2',44);
    $async->drain();
    $frozenDocument->result(); $frozenKey->result();
    $check(file_get_contents($directory . '/snapshot-document') === 'ORIGINAL', 'queued mutable objects snapshotted');
    $check(file_get_contents($directory . '/snapshot-key') === str_repeat('1',44), 'queued PHP scalar references detached');
    $async->fechar();

    // The queue destructor also rejects and cleans, without retaining futures.
    $ephemeral = new OperationQueue(1);
    $cancelOnDestruct = $ephemeral->submit($slow);
    unset($ephemeral);
    $expect(static fn() => $cancelOnDestruct->await(), 'FILA_FECHADA');

    // Mock native process is really reaped on cancellation through transport.
    $worker = $directory . '/mock-native';
    file_put_contents($worker, <<<'WORKER'
#!/usr/bin/env python3
import json,os,sys,time
req=json.loads(sys.stdin.readline())
open(os.environ['PID_FILE'],'w').write(str(os.getpid()))
print(json.dumps({'tipo':'envioIniciado','id':req['id']}),flush=True)
time.sleep(60)
WORKER
    );
    chmod($worker, 0700);
    $queue = $queues[] = new OperationQueue(1, 80);
    $native = $queue->submit(static function (callable $cancelled) use ($worker, $directory): FiscalResult {
        return (new ProcessTransport(5000))->execute($worker, $directory, ['PATH'=>'/usr/bin:/bin', 'PID_FILE'=>$directory . '/native-pid'], ['versao'=>1,'id'=>'async-native','sourceRevision'=>48590,'contractHash'=>str_repeat('a',64),'comando'=>'transmitir','config'=>[]], $cancelled);
    });
    $nativeFailure = $expect(static fn() => $native->await(), 'TIMEOUT_MOTOR');
    $check($nativeFailure->resultadoDesconhecido && $nativeFailure->limpezaConfirmada, 'native cancellation cleanup confirmed');
    $check(is_file($directory . '/native-pid') && !posix_kill((int)file_get_contents($directory . '/native-pid'), 0), 'native process no longer exists');
    $queue->close();

    // Competing process reapers cannot turn an old PID into a signal target.
    pcntl_signal(SIGCHLD, SIG_IGN);
    try { $expect(static fn() => new OperationQueue(), 'ASYNC_SIGNAL_INCOMPATIVEL'); }
    finally { pcntl_signal(SIGCHLD, SIG_DFL); }
    $queue = $queues[] = new OperationQueue();
    $handlerChanged = $queue->submit(static function () use ($directory, $result): FiscalResult {
        file_put_contents($directory . '/handler-pid', (string)getmypid());
        usleep(30000);
        return $result();
    }, false);
    pcntl_signal(SIGCHLD, SIG_IGN);
    try {
        usleep(60000);
        $lost = $expect(static fn() => $handlerChanged->await(), 'ASYNC_SIGNAL_INCOMPATIVEL');
        $check(!$lost->limpezaConfirmada, 'changed signal handler poisons instead of signalling stale PID');
    } finally { pcntl_signal(SIGCHLD, SIG_DFL); }
    $expect(static fn() => $queue->close(), 'FALHA_LIMPEZA_ASYNC');
    $queue = $queues[] = new OperationQueue();
    $stolen = $queue->submit(static function () use ($directory, $result): FiscalResult {
        file_put_contents($directory . '/stolen-pid', (string)getmypid());
        return $result();
    }, false);
    usleep(30000);
    $stolenPid = (int)file_get_contents($directory . '/stolen-pid');
    $check(pcntl_waitpid($stolenPid, $status, WNOHANG) === $stolenPid, 'simulate external application reaper');
    $lost = $expect(static fn() => $stolen->await(), 'FALHA_LIMPEZA_ASYNC');
    $check(!$lost->limpezaConfirmada, 'ECHILD loses ownership without sending signals');

    // Unsetting the facade has no closure cycle delaying queue destruction.
    $engine = new class($directory) implements Engine {
        public function __construct(private string $directory) {}
        public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
        {
            file_put_contents($this->directory . '/facade-destruct-pid', (string)getmypid());
            while (!$cancelled()) { usleep(1000); }
            throw new AcbrException('OPERACAO_CANCELADA', 'cancelled');
        }
    };
    $async = new AsyncEmitter(new Emitter(new Configuration('00000000000000', 'SP'), engine:$engine));
    $active = $async->validar('<mock/>');
    $pending = $async->validar('<mock/>');
    usleep(10000);
    unset($async);
    $expect(static fn() => $active->await(), 'FILA_FECHADA');
    $expect(static fn() => $pending->await(), 'FILA_FECHADA');
    $check(!posix_kill((int)file_get_contents($directory . '/facade-destruct-pid'), 0), 'facade destruction reaps active child promptly');

    // Absence of optional pcntl support is explicit, not a fake sync fallback.
    $program = 'require ' . var_export(__DIR__.'/bootstrap.php', true) . '; try { new Acbr\\Async\\OperationQueue(); exit(2); } catch (Acbr\\AcbrException $e) { exit($e->codigo === "ASYNC_INDISPONIVEL" ? 0 : 3); }';
    $process = proc_open([PHP_BINARY, '-d', 'disable_functions=pcntl_fork', '-r', $program], [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes);
    foreach ($pipes as $pipe) { fclose($pipe); }
    $check(proc_close($process) === 0, 'missing optional extension fails explicitly');
    foreach ([[0], [1002], [1,0], [1,120001], [1,1000,255]] as $arguments) { $expect(static fn() => new OperationQueue(...$arguments), 'CONFIGURACAO_INVALIDA'); }
} finally {
    foreach ($queues as $queue) { try { $queue->close(); } catch (Throwable) {} }
    foreach (glob($directory . '/*') ?: [] as $path) { unlink($path); }
    rmdir($directory);
}
echo "Async queue/future: {$count} assertions passed\n";
