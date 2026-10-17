<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';

use Acbr\AcbrException;
use Acbr\Async\AsyncEmitter;
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Contract;
use Acbr\Emitter;
use Acbr\Engine;
use Acbr\FiscalResult;
use Acbr\ProcessTransport;

// All XML, certificate bytes and identifiers below are protocol fixtures only.
// This suite does not load an A1, run the native worker, validate a signature,
// contact a service, or assert that PHP duplicates the native XSD/crypto checks.
$count = 0;
$check = static function (bool $condition, string $label) use (&$count): void {
    if (!$condition) { throw new RuntimeException('FAILED: '.$label); }
    ++$count;
};
$expect = static function (#[SensitiveParameter] callable $operation, string $code) use ($check): AcbrException {
    try { $operation(); throw new RuntimeException('Expected '.$code); }
    catch (AcbrException $error) {
        $check($error->codigo === $code, 'expected '.$code);
        return $error;
    }
};
$key = str_repeat('7', 44);
$protocol = str_repeat('8', 15);
$signed = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
    '<NFe xmlns="http://www.portalfiscal.inf.br/nfe"><infNFe Id="NFe'.$key.'"><SYNTHETIC>signed bytes only</SYNTHETIC></infNFe>'.
    '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignatureValue>SYNTHETIC_SIGNATURE_SENTINEL</SignatureValue></Signature></NFe>';
$processed = '<nfeProc xmlns="http://www.portalfiscal.inf.br/nfe" versao="4.00">'.
    substr($signed, strpos($signed, '<NFe')).
    '<protNFe versao="4.00"><infProt><tpAmb>2</tpAmb><chNFe>'.$key.'</chNFe><nProt>'.$protocol.'</nProt>'.
    '<digVal>SYNTHETIC_DIGEST_SENTINEL</digVal><cStat>100</cStat></infProt></protNFe></nfeProc>';
$success = static fn(): array => [
    'sucesso'=>true, 'estado'=>'XML_RECUPERADO', 'cStat'=>100, 'chave'=>$key,
    'protocolo'=>$protocol, 'xmlBase64'=>base64_encode($processed),
    'xMotivo'=>'SYNTHETIC_RAW_REASON_SENTINEL', 'envioIniciado'=>false,
];
$engine = new class($success) implements Engine {
    public array $requests = [];
    public mixed $lastCancelled = null;
    public ?AcbrException $failure = null;
    public function __construct(private Closure $response) {}
    public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $this->requests[] = $request;
        $this->lastCancelled = $cancelled;
        if ($this->failure !== null) { throw $this->failure; }
        return new FiscalResult(($this->response)(), $request['comando']);
    }
};
$certificate = CertificateA1::fromData('SYNTHETIC_PFX_SENTINEL', 'SYNTHETIC_PASSWORD_SENTINEL');
$config = new Configuration('11222333000181', 'PE', modelo:55, ambiente:2, certificado:$certificate);
$emitter = new Emitter($config, engine:$engine);
$probe = static fn(): bool => false;
$result = $emitter->recuperarXml($signed, $probe);
$request = $engine->requests[0];
$check(count($engine->requests) === 1, 'one injected engine call');
$check($request['comando'] === 'recuperarXml', 'native recovery command');
$check($request['xmlBase64'] === base64_encode($signed), 'signed bytes are unchanged on the wire');
$check($request['versao'] === 1 && $request['sourceRevision'] === 48590 && $request['contractHash'] === Contract::CONTRACT_HASH, 'shared wire contract');
$check($request['config']['uf'] === 'PE' && $request['config']['modelo'] === 55 && $request['config']['ambiente'] === 2, 'configuration remains unchanged');
$check($request['config']['certificado'] === ['pfxBase64'=>base64_encode('SYNTHETIC_PFX_SENTINEL'), 'senha'=>'SYNTHETIC_PASSWORD_SENTINEL'], 'opaque synthetic certificate uses stdin payload');
$check($engine->lastCancelled === $probe, 'cancellation probe reaches injected engine');
$check(!isset($request['chave'], $request['recibo'], $request['documento']), 'recovery does not invent a receipt or rebuild a document');
$check($result->comando() === 'recuperarXml' && $result->estado() === 'XML_RECUPERADO' && $result->sucesso(), 'recovery operation metadata');
$check($result->statusEnvio() === 'autorizado' && !$result->resultadoDesconhecido(), 'complete recovery status');
$check($result->xml() === $processed, 'processed XML requires explicit access');
$original = $result->retornoOriginal();
$check($original['origem'] === 'frame_nativo' && $original['retornoWebServiceOriginalDisponivel'] === false, 'original accessor does not claim raw SOAP');
$check($original['cStat'] === 100 && $original['chave'] === $key && $original['protocolo'] === $protocol, 'original accessor preserves fiscal fields');
$check($original['xMotivo'] === 'SYNTHETIC_RAW_REASON_SENTINEL' && $original['xmlBase64'] === base64_encode($processed) && $original['xmlDecodificado'] === $processed, 'original accessor preserves explicit raw content');
$check($result->data()['xmlBase64'] === base64_encode($processed), 'full frame remains explicitly available');
$safe = json_encode($result, JSON_THROW_ON_ERROR).print_r($result, true).print_r($emitter, true);
foreach ([$key, $protocol, $signed, $processed, 'SYNTHETIC_RAW_REASON_SENTINEL', 'SYNTHETIC_SIGNATURE_SENTINEL', 'SYNTHETIC_PFX_SENTINEL', 'SYNTHETIC_PASSWORD_SENTINEL'] as $secret) {
    $check(!str_contains($safe, $secret), 'default result and emitter summaries redact content');
}
$second = $emitter->recuperarXml($signed);
$check(count($engine->requests) === 2 && $request['id'] !== $engine->requests[1]['id'], 'separate explicit calls have distinct request IDs');
$before = count($engine->requests);
foreach (['', " \n", "\xff", str_repeat('x', 2097153)] as $bad) {
    $expect(static fn() => $emitter->recuperarXml($bad), 'XML_INVALIDO');
}
$cancelled = $expect(static fn() => $emitter->recuperarXml($signed, cancelled:static fn(): bool => true), 'OPERACAO_CANCELADA');
$check(!$cancelled->resultadoDesconhecido, 'pre-cancelled recovery never has a fiscal effect');
$check(count($engine->requests) === $before, 'invalid and pre-cancelled input never reaches the engine');
$without = new Emitter(new Configuration('11222333000181', 'PE'), engine:$engine);
$expect(static fn() => $without->recuperarXml($signed), 'CERTIFICADO_AUSENTE');
$check(count($engine->requests) === $before, 'certificate required before engine execution');

ini_set('zend.exception_ignore_args', '0');
try { $without->recuperarXml('<NFe>RECOVERY_TRACE_PRIVATE_SENTINEL</NFe>'); }
catch (AcbrException $error) {
    $check(!str_contains(print_r($error->getTrace(), true), 'RECOVERY_TRACE_PRIVATE_SENTINEL'), 'new public method redacts trace arguments');
}
ini_set('zend.exception_ignore_args', '1');
$engine->failure = new AcbrException('TIMEOUT_MOTOR', 'Synthetic local timeout.', false, true);
$timeout = $expect(static fn() => $emitter->recuperarXml($signed), 'TIMEOUT_MOTOR');
$check(!$timeout->resultadoDesconhecido && count($engine->requests) === $before + 1, 'recovery timeout is not retried or promoted to a fiscal send');
$engine->failure = null;
$emitter->fechar();
$expect(static fn() => $emitter->recuperarXml($signed), 'EMISSOR_FECHADO');

// Result classification remains conservative for non-100 or incomplete recovery.
foreach ([217, 150, 101, 135] as $code) {
    $failure = new FiscalResult(['sucesso'=>false, 'estado'=>'CONSULTADO', 'codigo'=>'RECUPERACAO_NAO_COMPROVADA', 'cStat'=>$code, 'chave'=>$key, 'envioIniciado'=>false], 'recuperarXml');
    $check(!$failure->sucesso() && $failure->xml() === null && $failure->statusEnvio() !== 'autorizado', 'non-100 does not prove recovery');
}
foreach ([
    ['cStat'=>150], ['estado'=>'AUTORIZADO'], ['sucesso'=>false], ['chave'=>'bad'],
    ['protocolo'=>str_repeat('8', 17)], ['envioIniciado'=>true], ['xmlBase64'=>''],
    ['xmlBase64'=>'eA==='], ['xmlBase64'=>'eB=='], ['xmlBase64'=>base64_encode("<nfeProc>\0</nfeProc>")],
    ['xmlBase64'=>base64_encode("\xff")], ['xmlBase64'=>base64_encode(str_repeat('z', 4194305))],
] as $change) {
    $check((new FiscalResult(array_replace($success(), $change), 'recuperarXml'))->statusEnvio() !== 'autorizado', 'incoherent recovery is not confirmed');
}
foreach (['chave', 'protocolo', 'xmlBase64'] as $missing) {
    $data = $success(); unset($data[$missing]);
    $check((new FiscalResult($data, 'recuperarXml'))->statusEnvio() !== 'autorizado', 'missing recovery evidence is not confirmed');
}
foreach ([100, 150] as $code) {
    $query = new FiscalResult(['sucesso'=>true, 'estado'=>'AUTORIZADO', 'cStat'=>$code, 'chave'=>$key, 'protocolo'=>$protocol, 'envioIniciado'=>false], 'consultar');
    $check($query->statusEnvio() === 'autorizado', 'consultar authorization semantics are unchanged');
}
$check((new FiscalResult($success(), 'consultar'))->statusEnvio() !== 'autorizado', 'new recovered state is scoped to recovery');
foreach (['CONTRATO_INCOMPATIVEL', 'LIMITE_EXCEDIDO', 'REQUISICAO_INVALIDA', 'DEPENDENCIA_AUSENTE', 'SERVICOS_AUSENTES', 'FALHA_MOTOR'] as $code) {
    $failure = new FiscalResult([
        'sucesso'=>false, 'estado'=>'ERRO', 'codigo'=>$code, 'envioIniciado'=>false,
        'xMotivo'=>'SYNTHETIC_KNOWN_CODE_REASON_SECRET', 'chave'=>$key,
    ], 'recuperarXml');
    $summary = $failure->jsonSerialize();
    $check(($summary['codigo'] ?? null) === $code, 'documented native recovery code remains useful in safe JSON');
    $check(!str_contains(json_encode($summary, JSON_THROW_ON_ERROR).print_r($failure, true), 'SYNTHETIC_KNOWN_CODE_REASON_SECRET'), 'documented code does not expose its original raw reason');
}

// A portable fake Engine above covers the API. Linux-only mocked JSONL below
// exercises actual pipe validation without any native runtime or network.
if (PHP_OS_FAMILY === 'Linux' && function_exists('proc_open')) {
    $directory = sys_get_temp_dir().'/acbr-php-recover-'.bin2hex(random_bytes(8));
    mkdir($directory, 0700);
    $worker = $directory.'/worker';
    $workerCode = <<<'WORKER'
<?php
declare(strict_types=1);
$request = json_decode((string)fgets(STDIN), true, 64, JSON_THROW_ON_ERROR);
$mode = getenv('ACBR_RECOVERY_TEST_MODE') ?: 'ok';
file_put_contents((string)getenv('ACBR_RECOVERY_ATTEMPTS'), 'x', FILE_APPEND | LOCK_EX);
if ($mode === 'hang') { while (true) { usleep(10000); } }
$frame = array_intersect_key($request, array_flip(['versao','id','sourceRevision','contractHash']));
$frame += [
    'sucesso'=>true, 'estado'=>'XML_RECUPERADO', 'cStat'=>100, 'chave'=>str_repeat('7',44),
    'protocolo'=>str_repeat('8',15), 'xmlBase64'=>base64_encode('<nfeProc>SYNTHETIC_PROCESSED_SENTINEL</nfeProc>'),
    'xMotivo'=>'SYNTHETIC_RAW_REASON_SENTINEL', 'envioIniciado'=>false,
];
if (in_array($mode, ['failure217','failure150','failureXml','failureProtocol'], true)) {
    $frame = array_replace($frame, ['sucesso'=>false,'estado'=>'CONSULTADO','codigo'=>'RECUPERACAO_NAO_COMPROVADA','cStat'=>$mode === 'failure150' ? 150 : 217]);
    unset($frame['xmlBase64'], $frame['protocolo']);
}
switch ($mode) {
    case 'secretCode': $frame['codigo'] = 'SYNTHETIC_RAW_CODE_SECRET_SENTINEL'; break;
    case 'success150': $frame['cStat'] = 150; break;
    case 'wrongState': $frame['estado'] = 'AUTORIZADO'; break;
    case 'falseRecovered': $frame['sucesso'] = false; break;
    case 'falseAuthorized': $frame = array_replace($frame, ['sucesso'=>false, 'estado'=>'AUTORIZADO']); break;
    case 'falseGenerated': $frame = array_replace($frame, ['sucesso'=>false, 'estado'=>'XML_GERADO']); break;
    case 'falseCancelled': $frame = array_replace($frame, ['sucesso'=>false, 'estado'=>'CANCELADO']); break;
    case 'nativeError':
    case 'nativeErrorXml':
    case 'nativeErrorProtocol':
    case 'unprovedError':
        $frame = array_replace($frame, ['sucesso'=>false, 'estado'=>'ERRO', 'codigo'=>'XML_NAO_ASSINADO']);
        unset($frame['cStat'], $frame['chave'], $frame['protocolo'], $frame['xmlBase64']);
        if ($mode === 'nativeErrorXml') { $frame['xmlBase64'] = base64_encode('<private/>'); }
        if ($mode === 'nativeErrorProtocol') { $frame['protocolo'] = str_repeat('8',15); }
        if ($mode === 'unprovedError') { $frame['codigo'] = 'RECUPERACAO_NAO_COMPROVADA'; }
        break;
    case 'noXml': unset($frame['xmlBase64']); break;
    case 'noKey': unset($frame['chave']); break;
    case 'noProtocol': unset($frame['protocolo']); break;
    case 'badKey': $frame['chave'] = 'bad'; break;
    case 'protocol17': $frame['protocolo'] = str_repeat('8',17); break;
    case 'cStatBool': $frame['cStat'] = true; break;
    case 'sending': $frame['envioIniciado'] = true; break;
    case 'marker': echo json_encode(['tipo'=>'envioIniciado','id'=>$request['id']]), "\n"; break;
    case 'base64': $frame['xmlBase64'] = 'eA==='; break;
    case 'base64Noncanonical': $frame['xmlBase64'] = 'eB=='; break;
    case 'xmlUtf8': $frame['xmlBase64'] = base64_encode("\xff"); break;
    case 'xmlNul': $frame['xmlBase64'] = base64_encode("<nfeProc>\0</nfeProc>"); break;
    case 'failureXml': $frame['xmlBase64'] = base64_encode('<private/>'); break;
    case 'failureProtocol': $frame['protocolo'] = str_repeat('8',15); break;
    case 'wrongId': $frame['id'] = 'other'; break;
    case 'wrongContract': $frame['contractHash'] = str_repeat('b',64); break;
}
echo json_encode($frame, JSON_THROW_ON_ERROR), "\n";
WORKER;
    file_put_contents($worker, '#!'.PHP_BINARY."\n".$workerCode);
    chmod($worker, 0700);
    $wire = ['versao'=>1, 'id'=>'synthetic-recovery', 'sourceRevision'=>48590, 'contractHash'=>Contract::CONTRACT_HASH, 'comando'=>'recuperarXml', 'config'=>[], 'xmlBase64'=>base64_encode($signed)];
    $call = static function (string $mode, ?array $request = null, int $timeoutMs = 1000, ?callable $cancelled = null) use ($directory, $worker, $wire): FiscalResult {
        return (new ProcessTransport($timeoutMs))->execute($worker, $directory, ['PATH'=>'/usr/local/bin:/usr/bin:/bin', 'ACBR_RECOVERY_TEST_MODE'=>$mode, 'ACBR_RECOVERY_ATTEMPTS'=>$directory.'/attempts'], $request ?? $wire, $cancelled);
    };
    try {
        $wireResult = $call('ok');
        $check($wireResult->comando() === 'recuperarXml' && $wireResult->statusEnvio() === 'autorizado', 'JSONL recovery succeeds with operation metadata');
        $check($wireResult->retornoOriginal()['xmlDecodificado'] === '<nfeProc>SYNTHETIC_PROCESSED_SENTINEL</nfeProc>', 'original result survives JSONL');
        foreach (['failure217'=>217, 'failure150'=>150] as $mode => $code) {
            $failure = $call($mode);
            $check(!$failure->sucesso() && $failure->estado() === 'CONSULTADO' && $failure->data()['cStat'] === $code, 'JSONL non-100 remains unproved');
            $check($failure->xml() === null && !isset($failure->data()['protocolo']), 'non-100 has no fabricated processed XML or protocol');
        }
        $nativeError = $call('nativeError');
        $check(!$nativeError->sucesso() && $nativeError->estado() === 'ERRO' && $nativeError->data()['codigo'] === 'XML_NAO_ASSINADO', 'native recovery error remains a valid non-success result');
        $check($nativeError->xml() === null && !str_contains(json_encode($nativeError, JSON_THROW_ON_ERROR).print_r($nativeError, true), 'SYNTHETIC_RAW_REASON_SENTINEL'), 'native error has no processed XML or public raw reason');
        $unprovedError = $call('unprovedError');
        $check(!$unprovedError->sucesso() && $unprovedError->estado() === 'ERRO' && !isset($unprovedError->data()['cStat']), 'unproved error without a fiscal status remains a valid non-success result');
        $secretResult = $call('secretCode');
        $check($secretResult->data()['codigo'] === 'SYNTHETIC_RAW_CODE_SECRET_SENTINEL', 'raw code remains explicitly available');
        $public = json_encode($secretResult, JSON_THROW_ON_ERROR).print_r($secretResult, true);
        foreach (['SYNTHETIC_RAW_CODE_SECRET_SENTINEL', 'SYNTHETIC_RAW_REASON_SENTINEL', 'SYNTHETIC_PROCESSED_SENTINEL'] as $secret) {
            $check(!str_contains($public, $secret), 'untrusted worker fields cannot escape recovery summaries');
        }
        foreach (['success150','wrongState','falseRecovered','falseAuthorized','falseGenerated','falseCancelled','noXml','noKey','noProtocol','badKey','protocol17','cStatBool','sending','marker','base64','base64Noncanonical','xmlUtf8','xmlNul','failureXml','failureProtocol','nativeErrorXml','nativeErrorProtocol','wrongId','wrongContract'] as $mode) {
            $error = $expect(static fn() => $call($mode), 'RESPOSTA_INVALIDA');
            $check($error->limpezaConfirmada, 'invalid JSONL child cleanup');
            $check(!str_contains(json_encode($error, JSON_THROW_ON_ERROR).print_r($error, true), 'SYNTHETIC_RAW_REASON_SENTINEL'), 'invalid recovery response remains redacted');
        }
        $expect(static fn() => $call('ok', array_replace($wire, ['comando'=>'consultar'])), 'RESPOSTA_INVALIDA');
        $attempts = strlen((string)file_get_contents($directory.'/attempts'));
        $timeout = $expect(static fn() => $call('hang', timeoutMs:100), 'TIMEOUT_MOTOR');
        $check(!$timeout->resultadoDesconhecido && $timeout->limpezaConfirmada && $timeout->xmlRecuperacao() === null, 'read-only recovery timeout is not a mutating fiscal uncertainty');
        $check(strlen((string)file_get_contents($directory.'/attempts')) === $attempts + 1, 'transport does not retry recovery');
        $start = hrtime(true);
        $cancelled = $expect(static fn() => $call('hang', cancelled:static fn(): bool => hrtime(true) - $start >= 80000000), 'OPERACAO_CANCELADA');
        $check(!$cancelled->resultadoDesconhecido && $cancelled->limpezaConfirmada, 'running recovery cancellation remains read-only');
    } finally {
        foreach ([$worker, $directory.'/attempts'] as $path) { if (is_file($path)) { unlink($path); } }
        rmdir($directory);
    }
}

if (PHP_OS_FAMILY === 'Linux' && function_exists('pcntl_fork') && function_exists('posix_kill')) {
    $asyncEngine = new class($success) implements Engine {
        public function __construct(private Closure $response) {}
        public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
        {
            return new FiscalResult(($this->response)(), $request['comando']);
        }
    };
    $async = new AsyncEmitter(new Emitter($config, engine:$asyncEngine));
    try {
        $recovered = $async->recuperarXml($signed)->await();
        $check($recovered->comando() === 'recuperarXml' && $recovered->statusEnvio() === 'autorizado', 'async facade preserves recovery command through IPC');
        $check($recovered->retornoOriginal()['xmlDecodificado'] === $processed, 'explicit original result survives async IPC');
        $check(!str_contains(json_encode($async, JSON_THROW_ON_ERROR).print_r($async, true), 'SYNTHETIC_PASSWORD_SENTINEL'), 'async facade remains redacted');
    } finally { $async->fechar(); }
    foreach (['XML_NAO_ASSINADO', 'XML_SCHEMA_INVALIDO', 'TLS_SERVIDOR_INVALIDO', 'CONTRATO_INCOMPATIVEL', 'LIMITE_EXCEDIDO', 'DEPENDENCIA_AUSENTE', 'SERVICOS_AUSENTES'] as $code) {
        $errorEngine = new class($code) implements Engine {
            public function __construct(private string $code) {}
            public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
            {
                throw new AcbrException($this->code, 'SYNTHETIC_ASYNC_NATIVE_REASON_SECRET', false, true);
            }
        };
        $async = new AsyncEmitter(new Emitter($config, engine:$errorEngine));
        try {
            $failure = $expect(static fn() => $async->recuperarXml($signed)->await(), $code);
            $check(!$failure->resultadoDesconhecido && $failure->limpezaConfirmada, 'native recovery error metadata survives async IPC');
            $check(!str_contains(json_encode($failure, JSON_THROW_ON_ERROR).print_r($failure, true), 'SYNTHETIC_ASYNC_NATIVE_REASON_SECRET'), 'native recovery error message does not cross async IPC');
        } finally { $async->fechar(); }
    }
    $slowEngine = new class implements Engine {
        public function execute(#[SensitiveParameter] array $request, #[SensitiveParameter] ?callable $cancelled = null): FiscalResult
        {
            while ($cancelled !== null && !$cancelled()) { usleep(1000); }
            throw new AcbrException('OPERACAO_CANCELADA', 'SYNTHETIC_ASYNC_CALLBACK_SECRET', false, true);
        }
    };
    $async = new AsyncEmitter(new Emitter($config, engine:$slowEngine), capacity:2, timeoutMs:80);
    try {
        $active = $async->recuperarXml($signed);
        $pending = $async->recuperarXml($signed);
        $check($pending->cancel(), 'queued recovery cancellation is accepted');
        $cancelled = $expect(static fn() => $pending->await(), 'OPERACAO_CANCELADA');
        $check(!$cancelled->resultadoDesconhecido, 'queued recovery never has a fiscal effect');
        $timeout = $expect(static fn() => $active->await(), 'TIMEOUT_MOTOR');
        $check(!$timeout->resultadoDesconhecido && $timeout->limpezaConfirmada, 'async recovery timeout is read-only with confirmed cleanup');
        $check(!str_contains(json_encode($timeout, JSON_THROW_ON_ERROR).print_r($timeout, true), 'SYNTHETIC_ASYNC_CALLBACK_SECRET'), 'async recovery exception messages remain redacted');
    } finally { $async->fechar(); }
    $async = new AsyncEmitter(new Emitter($config, engine:$slowEngine), timeoutMs:1000);
    try {
        $active = $async->recuperarXml($signed);
        $check($active->cancel(), 'running recovery cancellation is accepted');
        $cancelled = $expect(static fn() => $active->await(), 'OPERACAO_CANCELADA');
        $check(!$cancelled->resultadoDesconhecido && $cancelled->limpezaConfirmada, 'running async recovery cancellation does not imply a fiscal send');
    } finally { $async->fechar(); }
}

echo "Recovery API: {$count} assertions passed; synthetic Engine/JSONL only, no native runtime or network\n";
