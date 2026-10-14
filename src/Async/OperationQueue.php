<?php
declare(strict_types=1);
namespace Acbr\Async;

use Acbr\AcbrException;
use Acbr\FiscalResult;

/**
 * Optional Linux CLI fork backend. One running operation per queue, no retries.
 * submit() starts real work before returning; tick()/await()/drain() drive FIFO
 * continuation and bounded supervision. Do not fork an already threaded runtime.
 */
final class OperationQueue implements \JsonSerializable
{
    /** @var array<int, array{future:Future, operation:\Closure, fiscal:bool}> */
    private array $pending = [];
    private ?array $running = null;
    private bool $closed = false;
    private bool $cleanupConfirmed = true;
    private bool $pumping = false;
    private int $sequence = 0;
    private readonly int $ownerPid;

    public function __construct(
        private readonly int $capacity = 16,
        private readonly int $timeoutMs = 120000,
        private readonly int $maxBytes = 8388608,
        private readonly int $cancellationGraceMs = 1200,
    ) {
        if ($capacity < 1 || $capacity > 1001 || $timeoutMs < 1 || $timeoutMs > 120000 || $maxBytes < 256 || $maxBytes > 8388608 || $cancellationGraceMs < 1 || $cancellationGraceMs > 5000) {
            throw new AcbrException('CONFIGURACAO_INVALIDA', 'Configuração da fila assíncrona inválida.');
        }
        if (PHP_OS_FAMILY !== 'Linux' || PHP_SAPI !== 'cli') {
            throw new AcbrException('ASYNC_INDISPONIVEL', 'A fila assíncrona exige PHP CLI no Linux.');
        }
        foreach (['pcntl_fork','pcntl_waitpid','pcntl_signal','pcntl_signal_get_handler','posix_setpgid','posix_getpgid','posix_getppid','posix_kill','stream_socket_pair'] as $function) {
            if (!function_exists($function)) { throw new AcbrException('ASYNC_INDISPONIVEL', 'A fila assíncrona exige pcntl, posix e sockets locais habilitados.'); }
        }
        if (pcntl_signal_get_handler(SIGCHLD) !== SIG_DFL) { throw $this->error('ASYNC_SIGNAL_INCOMPATIVEL'); }
        $this->ownerPid = getmypid();
    }

    /**
     * Callback receives a cancellation probe and must return FiscalResult.
     * Pass that probe to the Emitter method. Treat captured objects as snapshots:
     * child-side changes are not propagated back into this PHP process.
     * Capacity includes the running operation; overflow fails before execution.
     */
    public function submit(#[\SensitiveParameter] callable $operation, bool $mayHaveFiscalEffect = true): Future
    {
        $this->assertOwner();
        if ($this->closed) { throw $this->error('FILA_FECHADA', false, $this->cleanupConfirmed); }
        $this->tick();
        if ($this->closed) { throw $this->error('FILA_FECHADA', false, $this->cleanupConfirmed); }
        if (count($this->pending) + ($this->running !== null ? 1 : 0) >= $this->capacity) { throw $this->error('FILA_CHEIA'); }
        $id = ++$this->sequence;
        $future = new Future($this, $id);
        $this->pending[$id] = ['future'=>$future, 'operation'=>\Closure::fromCallable($operation), 'fiscal'=>$mayHaveFiscalEffect];
        $this->startNext();
        return $future;
    }

    /** Polls progress; waiting is optional. Cleanup can take up to one second. */
    public function tick(int $waitMs = 0): void
    {
        $this->assertOwner();
        if ($waitMs < 0 || $waitMs > 1000) { throw $this->error('CONFIGURACAO_INVALIDA'); }
        if ($this->pumping) { return; }
        $this->pumping = true;
        try {
            $this->startNext();
            if ($this->running === null) { return; }
            if (pcntl_signal_get_handler(SIGCHLD) !== SIG_DFL) { $this->stopRunning('ASYNC_SIGNAL_INCOMPATIVEL'); }
            $now = hrtime(true);
            if ($this->running['stop'] === null && $now >= $this->running['deadline']) { $this->stopRunning('TIMEOUT_MOTOR'); }
            if ($this->running['stop'] !== null && $now >= $this->running['stopDeadline']) {
                $this->finish(null, $this->error($this->running['stop'], $this->running['fiscal']));
                return;
            }
            $deadline = $this->running['stop'] !== null ? $this->running['stopDeadline'] : $this->running['deadline'];
            $micros = min($waitMs * 1000, max(0, (int)(($deadline - hrtime(true)) / 1000)));
            $read = [$this->running['socket']]; $write = []; $except = [];
            $selected = @stream_select($read, $write, $except, intdiv($micros, 1000000), $micros % 1000000);
            if ($selected === false) { return; } // Interrupted system calls are retried on the next tick.
            if ($selected > 0) {
                $chunk = @fread($this->running['socket'], 65536);
                if ($chunk === false) { $this->finish(null, $this->error('FALHA_PIPE', $this->running['fiscal'])); return; }
                $this->running['buffer'] .= $chunk;
                if (strlen($this->running['buffer']) > $this->maxBytes) { $this->finish(null, $this->error('RESPOSTA_EXCESSIVA', $this->running['fiscal'])); return; }
                if (str_contains($this->running['buffer'], "\n")) {
                    $this->receive();
                } elseif (feof($this->running['socket'])) {
                    $this->finish(null, $this->error('FALHA_ASYNC', $this->running['fiscal']));
                }
            }
        } finally {
            $this->pumping = false;
            $this->startNext();
        }
    }

    /** Waits until every accepted operation has settled. Inspect each future. */
    public function drain(): void
    {
        $this->assertOwner();
        while ($this->running !== null || $this->pending !== []) { $this->tick(20); }
    }

    /** @internal Called by Future::cancel(). */
    public function cancel(int $id): bool
    {
        $this->assertOwner();
        $this->tick();
        if (isset($this->pending[$id])) {
            $this->pending[$id]['future']->reject($this->error('OPERACAO_CANCELADA'));
            unset($this->pending[$id]);
            return true;
        }
        if ($this->running !== null && $this->running['id'] === $id) {
            $this->stopRunning('OPERACAO_CANCELADA');
            return true;
        }
        return false;
    }

    /** Cancels all accepted operations and waits for bounded worker cleanup. */
    public function close(): void
    {
        $this->assertOwner();
        $this->closed = true;
        $this->rejectPending('FILA_FECHADA');
        if ($this->running !== null) { $this->stopRunning('FILA_FECHADA'); $this->drain(); }
        if (!$this->cleanupConfirmed) { throw $this->error('FALHA_LIMPEZA_ASYNC', true, false); }
    }

    public function jsonSerialize(): array
    {
        return ['closed'=>$this->closed, 'running'=>$this->running !== null, 'pending'=>count($this->pending), 'capacity'=>$this->capacity, 'cleanupConfirmed'=>$this->cleanupConfirmed];
    }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
    public function __destruct()
    {
        if (isset($this->ownerPid) && getmypid() === $this->ownerPid) { try { $this->close(); } catch (\Throwable) {} }
    }

    private function assertOwner(): void
    {
        if (getmypid() !== $this->ownerPid) { throw $this->error('FILA_PROCESSO_INVALIDO'); }
    }

    private function startNext(): void
    {
        if ($this->closed || $this->running !== null || $this->pending === []) { return; }
        if (pcntl_signal_get_handler(SIGCHLD) !== SIG_DFL) {
            $this->closed = true; $this->rejectPending('ASYNC_SIGNAL_INCOMPATIVEL'); return;
        }
        $id = array_key_first($this->pending);
        $job = $this->pending[$id]; unset($this->pending[$id]);
        $pair = @stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        if ($pair === false) { $job['future']->reject($this->error('FALHA_INICIAR_ASYNC')); return; }
        $pid = @pcntl_fork();
        if ($pid === -1) {
            fclose($pair[0]); fclose($pair[1]);
            $job['future']->reject($this->error('FALHA_INICIAR_ASYNC')); return;
        }
        if ($pid === 0) {
            fclose($pair[0]);
            $this->runChild($pair[1], $job['operation'], $job['fiscal']);
        }
        fclose($pair[1]);
        // No operation is allowed to start until the child itself has confirmed
        // creating a private group. Never signal a parent's/inherited group.
        stream_set_timeout($pair[0], 1);
        $ready = @fread($pair[0], 1);
        $ownedGroup = $ready === 'R' && @posix_getpgid($pid) === $pid;
        if (!$ownedGroup) {
            $clean = $this->terminateAndReap($pid, false);
            fclose($pair[0]);
            $job['future']->reject($this->error('FALHA_INICIAR_ASYNC', false, $clean));
            if (!$clean) { $this->poison(); }
            return;
        }
        stream_set_blocking($pair[0], false);
        $now = hrtime(true);
        $this->running = ['id'=>$id, 'future'=>$job['future'], 'fiscal'=>$job['fiscal'], 'pid'=>$pid, 'socket'=>$pair[0], 'buffer'=>'', 'deadline'=>$now + $this->timeoutMs * 1000000, 'stop'=>null, 'stopAt'=>0, 'stopDeadline'=>0];
        $job['future']->markRunning();
        if (@fwrite($pair[0], 'G') !== 1) { $this->finish(null, $this->error('FALHA_INICIAR_ASYNC')); }
    }

    private function runChild($socket, #[\SensitiveParameter] \Closure $operation, bool $fiscal): never
    {
        // Do not run inherited shutdown handlers/destructors: terminate with a
        // signal after sending the complete envelope, rather than calling exit.
        ini_set('display_errors', '0'); ini_set('log_errors', '0');
        ob_start(static fn(string $output): string => '');
        @pcntl_signal(SIGCHLD, SIG_DFL);
        if (!@posix_setpgid(0, 0) || @fwrite($socket, 'R') !== 1) { $this->childExit($socket); }
        stream_set_timeout($socket, 2);
        if (@fread($socket, 1) !== 'G') { $this->childExit($socket); }
        stream_set_blocking($socket, false);
        $deadline = hrtime(true) + $this->timeoutMs * 1000000;
        $cancelled = false;
        $probe = function () use ($socket, $deadline, &$cancelled): bool {
            if ($cancelled) { return true; }
            $signal = @fread($socket, 1);
            $cancelled = $signal === 'C' || feof($socket) || posix_getppid() !== $this->ownerPid || hrtime(true) >= $deadline;
            return $cancelled;
        };
        try {
            $value = $operation($probe);
            if (!$value instanceof FiscalResult) { throw $this->error('RESULTADO_ASYNC_INVALIDO', $fiscal); }
            $envelope = ['ok'=>true, 'data'=>$value->data(), 'command'=>$value->comando()];
        } catch (\Throwable $error) {
            // Never serialize exceptions, arbitrary messages, traces or closures.
            $envelope = ['ok'=>false, 'code'=>$error instanceof AcbrException ? $this->safeCode($error->codigo) : 'FALHA_ASYNC', 'uncertain'=>$error instanceof AcbrException ? $error->resultadoDesconhecido : $fiscal, 'clean'=>$error instanceof AcbrException ? $error->limpezaConfirmada : true];
            if ($error instanceof AcbrException && $error->xmlRecuperacao() !== null) { $envelope['recovery'] = base64_encode($error->xmlRecuperacao()); }
        }
        $envelope['finished'] = hrtime(true);
        try { $bytes = json_encode($envelope, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"; }
        catch (\Throwable) { $bytes = $this->failureBytes('RESPOSTA_INVALIDA', $fiscal); }
        if (strlen($bytes) > $this->maxBytes) { $bytes = $this->failureBytes('RESPOSTA_EXCESSIVA', $fiscal); }
        // Keep a bounded result in memory under IPC backpressure. A completed
        // native operation is not lost just because the owner polls later.
        $offset = 0;
        while ($offset < strlen($bytes) && posix_getppid() === $this->ownerPid) {
            $read = []; $write = [$socket]; $except = [];
            if (@stream_select($read, $write, $except, 0, 20000) > 0) {
                $written = @fwrite($socket, substr($bytes, $offset, 65536));
                if ($written === false) { break; }
                $offset += $written;
            }
        }
        $bytes = ''; $envelope = [];
        $this->childExit($socket);
    }

    private function childExit($socket): never
    {
        if (is_resource($socket)) { @fclose($socket); }
        @posix_kill(getmypid(), SIGKILL);
        // SIGKILL cannot be caught. This fallback is unreachable on Linux.
        exit(255);
    }

    private function failureBytes(string $code, bool $uncertain): string
    {
        return json_encode(['ok'=>false, 'code'=>$code, 'uncertain'=>$uncertain, 'clean'=>true, 'finished'=>hrtime(true)], JSON_THROW_ON_ERROR) . "\n";
    }

    private function receive(): void
    {
        try { $frame = json_decode($this->running['buffer'], true, 64, JSON_THROW_ON_ERROR); }
        catch (\JsonException) { $this->finish(null, $this->error('RESPOSTA_INVALIDA', $this->running['fiscal'])); return; }
        if (!is_array($frame) || !is_bool($frame['ok'] ?? null) || !is_int($frame['finished'] ?? null)) { $this->finish(null, $this->error('RESPOSTA_INVALIDA', $this->running['fiscal'])); return; }
        if ($frame['finished'] > $this->running['deadline'] && $this->running['stop'] === null) { $this->stopRunning('TIMEOUT_MOTOR'); }
        if ($this->running['stop'] === 'TIMEOUT_MOTOR' && $frame['finished'] <= $this->running['deadline']) { $this->running['stop'] = null; }
        if ($frame['ok'] === true && is_array($frame['data'] ?? null) && is_bool($frame['data']['sucesso'] ?? null) && is_string($frame['data']['estado'] ?? null)) {
            $command = $frame['command'] ?? null;
            if ($command !== null && (!is_string($command) || !in_array($command, ['gerarXml','validar','assinar','transmitir','consultar','cancelar','cartaCorrecao','inutilizar'], true))) { $this->finish(null, $this->error('RESPOSTA_INVALIDA', $this->running['fiscal'])); return; }
            $this->finish(new FiscalResult($frame['data'], $command), null);
        } elseif ($frame['ok'] === false && is_string($frame['code'] ?? null) && is_bool($frame['uncertain'] ?? null) && is_bool($frame['clean'] ?? null)) {
            $recovery = $frame['recovery'] ?? null;
            if ($recovery !== null && (!is_string($recovery) || base64_encode(base64_decode($recovery, true) ?: '') !== $recovery)) { $this->finish(null, $this->error('RESPOSTA_INVALIDA', $this->running['fiscal'])); return; }
            $this->finish(null, $this->error($this->safeCode($frame['code']), $frame['uncertain'], $frame['clean'], $recovery));
        } else {
            $this->finish(null, $this->error('RESPOSTA_INVALIDA', $this->running['fiscal']));
        }
    }

    private function stopRunning(string $code): void
    {
        if ($this->running === null || $this->running['stop'] !== null) { return; }
        $this->running['stop'] = $code;
        $this->running['stopAt'] = hrtime(true);
        $this->running['stopDeadline'] = $this->running['stopAt'] + $this->cancellationGraceMs * 1000000;
        @fwrite($this->running['socket'], 'C');
    }

    private function finish(#[\SensitiveParameter] ?FiscalResult $result, #[\SensitiveParameter] ?AcbrException $error): void
    {
        $job = $this->running;
        if ($job === null) { return; }
        // Recheck ownership with waitpid before any signal. ECHILD, or a
        // competing signal handler, must never lead to signalling a stale PID.
        $clean = $this->terminateAndReap($job['pid'], true) && ($error?->limpezaConfirmada ?? true);
        fclose($job['socket']);
        $this->running = null;
        if ($job['stop'] !== null) { $error = $this->error($job['stop'], $job['fiscal'] || ($error?->resultadoDesconhecido ?? false), $clean, $error?->xmlRecuperacao() !== null ? base64_encode($error->xmlRecuperacao()) : null); }
        if (!$clean) {
            $error = $this->error($error?->codigo ?? 'FALHA_LIMPEZA_ASYNC', $job['fiscal'] || ($error?->resultadoDesconhecido ?? false), false, $error?->xmlRecuperacao() !== null ? base64_encode($error->xmlRecuperacao()) : null);
            $this->poison();
        }
        if ($error !== null) { $job['future']->reject($error); }
        elseif ($result !== null) { $job['future']->resolve($result); }
        else { $job['future']->reject($this->error('FALHA_ASYNC', $job['fiscal'])); }
    }

    private function terminateAndReap(int $pid, bool $group): bool
    {
        $owned = @pcntl_waitpid($pid, $status, WNOHANG);
        if ($owned === -1) { return false; } // Ownership lost: never signal.
        if ($owned === $pid) { return $this->reap($pid, $group, true); }
        if (pcntl_signal_get_handler(SIGCHLD) !== SIG_DFL) { return false; }
        // A live owned child anchors this PID/group. With the required default
        // SIGCHLD policy and no external reaper it cannot be reused before reap.
        @posix_kill($group ? -$pid : $pid, SIGKILL);
        return $this->reap($pid, $group);
    }

    private function reap(int $pid, bool $group, bool $reaped = false): bool
    {
        $deadline = hrtime(true) + 1000000000;
        do {
            if (!$reaped) {
                $owned = @pcntl_waitpid($pid, $status, WNOHANG);
                if ($owned === -1) { return false; }
                $reaped = $owned === $pid;
            }
            if ($reaped && (!$group || !@posix_kill(-$pid, 0))) { return true; }
            usleep(1000);
        } while (hrtime(true) < $deadline);
        return false;
    }

    private function poison(): void
    {
        $this->cleanupConfirmed = false; $this->closed = true;
        $this->rejectPending('FILA_FECHADA');
    }
    private function rejectPending(string $code): void
    {
        foreach ($this->pending as $job) { $job['future']->reject($this->error($code, false, $this->cleanupConfirmed)); }
        $this->pending = [];
    }
    private function safeCode(string $code): string
    {
        // Codes only; arbitrary exception text is never an IPC payload.
        return in_array($code, [
            'ASYNC_INDISPONIVEL','ASYNC_SIGNAL_INCOMPATIVEL','FALHA_ASYNC','FALHA_INICIAR_ASYNC','FALHA_LIMPEZA_ASYNC','RESULTADO_ASYNC_INVALIDO',
            'CONFIGURACAO_INVALIDA','CERTIFICADO_AUSENTE','CERTIFICADO_INVALIDO','CERTIFICADO_EXCESSIVO','CSC_INVALIDO',
            'CONTRATO_DIVERGENTE','EMISSOR_FECHADO','FILA_FECHADA','FILA_CHEIA','FILA_PROCESSO_INVALIDO',
            'FALHA_INICIAR_MOTOR','FALHA_MOTOR','FALHA_PIPE','OPERACAO_CANCELADA','PLATAFORMA_NAO_SUPORTADA',
            'PROC_OPEN_INDISPONIVEL','REQUISICAO_EXCESSIVA','REQUISICAO_INVALIDA','RESPOSTA_EXCESSIVA','RESPOSTA_INVALIDA',
            'RUNTIME_ALTERADO','RUNTIME_ARQUIVO_EXTRA','RUNTIME_ARQUIVO_INVALIDO','RUNTIME_AUSENTE','RUNTIME_CAMINHO_INVALIDO',
            'RUNTIME_DESTINO_EXISTENTE','RUNTIME_INCOMPLETO','RUNTIME_INSTALACAO_FALHOU','RUNTIME_INVALIDO','RUNTIME_LINK_INVALIDO',
            'RUNTIME_NAO_EXECUTAVEL','RUNTIME_PIN_INVALIDO','SEGREDO_NAO_SERIALIZAVEL','TIMEOUT_MOTOR','XML_INVALIDO','ZLIB_AUSENTE',
            'CHAVE_INVALIDA','RECIBO_INVALIDO','PROTOCOLO_INVALIDO','JUSTIFICATIVA_INVALIDA','CORRECAO_INVALIDA','INUTILIZACAO_INVALIDA',
            'OPERACAO_INVALIDA','OPERACAO_NAO_SUPORTADA',
        ], true) ? $code : 'FALHA_ASYNC';
    }
    private function error(string $code, bool $uncertain = false, bool $clean = true, #[\SensitiveParameter] ?string $recovery = null): AcbrException
    {
        $message = match ($code) {
            'ASYNC_SIGNAL_INCOMPATIVEL' => 'A fila exige SIGCHLD padrão e nenhum recolhimento externo de seus filhos.',
            'FILA_CHEIA' => 'A fila assíncrona atingiu sua capacidade.',
            'FILA_FECHADA' => 'A fila assíncrona está encerrada.',
            'OPERACAO_CANCELADA' => 'A operação assíncrona foi cancelada.',
            'TIMEOUT_MOTOR' => 'A operação assíncrona excedeu o prazo.',
            'FILA_PROCESSO_INVALIDO' => 'A fila só pode ser usada pelo processo PHP que a criou.',
            default => 'A operação assíncrona não pôde ser concluída. Consulte o código do erro.',
        };
        if (!$clean) { $message .= ' Encerramento não confirmado; interrompa o uso deste processo PHP.'; }
        if ($uncertain) { $message .= ' Consulte a situação fiscal antes de qualquer nova tentativa.'; }
        return new AcbrException($code, $message, $uncertain, $clean, $recovery);
    }
}
