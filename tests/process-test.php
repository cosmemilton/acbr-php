<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use Acbr\AcbrException;
use Acbr\ProcessTransport;
$count = 0;
$check = static function (bool $condition, string $name) use (&$count): void {
    if (!$condition) { throw new RuntimeException('FAILED: ' . $name); }
    $count++;
};
$req = ['versao'=>1,'id'=>'offline-test','sourceRevision'=>48590,'contractHash'=>str_repeat('a',64),'comando'=>'gerarXml','config'=>[]];
$call = static function (string $mode, int $timeout = 1000, int $limit = 8388608, ?callable $cancel = null, ?array $request = null) use ($req) {
    return (new ProcessTransport($timeout, $limit))->execute(__DIR__.'/fixtures/mock-worker', __DIR__, ['PATH'=>'/usr/local/bin:/usr/bin:/bin','ACBR_TEST_MODE'=>$mode], $request ?? $req, $cancel);
};
foreach (['ok','split','stderr'] as $mode) {
    $result = $call($mode);
    $check($result->sucesso() && $result->xml() === '<test/>', $mode);
    $check(!str_contains(json_encode($result), 'xmlBase64'), 'redacted ' . $mode);
}
foreach (['id','contract','base64','state','status','bool','json','utf8','duplicate','partial','empty','trailing','exit'] as $mode) {
    try { $call($mode); throw new RuntimeException('Expected failure ' . $mode); }
    catch (AcbrException $e) {
        $check(in_array($e->codigo, ['RESPOSTA_INVALIDA','FALHA_MOTOR'], true), $mode . ' code');
        $check($e->limpezaConfirmada && !$e->resultadoDesconhecido, $mode . ' cleanup');
        $check(!str_contains(json_encode($e), 'private-data'), $mode . ' redaction');
    }
}
foreach (['hang','no-read','send-hang'] as $mode) {
    $start = hrtime(true);
    try { $call($mode, 100, request:$mode === 'send-hang' ? array_replace($req, ['comando'=>'transmitir']) : $req); throw new RuntimeException('Expected timeout'); }
    catch (AcbrException $e) {
        $check($e->codigo === 'TIMEOUT_MOTOR', $mode . ' timeout');
        $check($e->limpezaConfirmada, $mode . ' killed');
        $check($e->resultadoDesconhecido === ($mode === 'send-hang'), $mode . ' uncertainty');
    }
    $check((hrtime(true) - $start) / 1e9 < 1.5, $mode . ' bounded');
}
try { $call('flood', 1000, 4096); throw new RuntimeException('Expected overflow'); }
catch (AcbrException $e) { $check($e->codigo === 'RESPOSTA_EXCESSIVA' && $e->limpezaConfirmada, 'stderr bound'); }
try { $call('ok', cancel:static fn()=>true); throw new RuntimeException('Expected cancelled'); }
catch (AcbrException $e) { $check($e->codigo === 'OPERACAO_CANCELADA', 'pre-cancel'); }
$start = hrtime(true);
try { $call('hang', cancel:static fn() => hrtime(true) - $start > 100000000); throw new RuntimeException('Expected cancelled'); }
catch (AcbrException $e) { $check($e->codigo === 'OPERACAO_CANCELADA' && $e->limpezaConfirmada, 'during cancellation'); }
try { $call('hang', 100, request:array_replace($req, ['comando'=>'transmitir'])); throw new RuntimeException('Expected uncertainty'); }
catch (AcbrException $e) { $check($e->resultadoDesconhecido, 'mock fiscal no blind retry'); }
try { $call('ok', limit:256, request:$req + ['large'=>str_repeat('x',500)]); throw new RuntimeException('Expected request bound'); }
catch (AcbrException $e) { $check($e->codigo === 'REQUISICAO_EXCESSIVA', 'request bytes'); }
$start = hrtime(true);
try { $call('no-read', 100, request:$req + ['documento'=>str_repeat('x', 2*1024*1024)]); throw new RuntimeException('Expected blocked stdin timeout'); }
catch (AcbrException $e) { $check($e->codigo === 'TIMEOUT_MOTOR' && $e->limpezaConfirmada, 'blocked large stdin'); }
$check((hrtime(true)-$start)/1e9 < 1.5, 'blocked stdin deadline');
ini_set('zend.exception_ignore_args', '0');
try {
    (new ProcessTransport())->execute(__DIR__.'/fixtures/mock-worker', __DIR__, ['PATH'=>'/usr/bin:/bin','ACBR_TEST_MODE'=>'json'], $req + ['documento'=>'TRACE_PRIVATE_SENTINEL']);
    throw new RuntimeException('Expected invalid response');
} catch (AcbrException $e) {
    $check(!str_contains(print_r($e->getTrace(), true), 'TRACE_PRIVATE_SENTINEL'), 'exception trace redaction');
}
try {
    $call('send-hang', 100, request:array_replace($req, ['comando'=>'transmitir','xmlBase64'=>base64_encode('<NFe>RECOVERY_SENTINEL</NFe>')]));
    throw new RuntimeException('Expected uncertain send');
} catch (AcbrException $e) {
    $check($e->resultadoDesconhecido && $e->xmlRecuperacao()==='<NFe>RECOVERY_SENTINEL</NFe>', 'recover XML on actual transport failure');
    $check(!str_contains(json_encode($e), 'RECOVERY_SENTINEL') && !str_contains(print_r($e,true), 'RECOVERY_SENTINEL'), 'recovery redacted in summaries');
}
$probes = 0;
try {
    $call('send-hang', 1000, cancel:static function () use (&$probes): bool { if (++$probes === 5) { throw new RuntimeException('CALLBACK_PRIVATE_SENTINEL'); } return false; }, request:array_replace($req, ['comando'=>'transmitir','xmlBase64'=>base64_encode('<NFe>RECOVERY_CALLBACK</NFe>')]));
    throw new RuntimeException('Expected callback failure');
} catch (AcbrException $e) {
    $check($e->resultadoDesconhecido && $e->xmlRecuperacao()==='<NFe>RECOVERY_CALLBACK</NFe>', 'callback failure preserves recovery');
    $check(!str_contains((string)$e, 'CALLBACK_PRIVATE_SENTINEL'), 'callback error sanitized');
}
$capture = 'CALLBACK_CAPTURE_SECRET_SENTINEL';
try {
    (new ProcessTransport(100))->execute(__DIR__.'/fixtures/mock-worker',__DIR__,['PATH'=>'/usr/bin:/bin','ACBR_TEST_MODE'=>'hang'],$req,static function () use ($capture): bool { return $capture === ''; });
    throw new RuntimeException('Expected timeout with callback');
} catch (AcbrException $e) {
    $check(!str_contains(print_r($e->getTrace(),true),$capture), 'callback closure captures redacted');
}
echo "Process transport: {$count} assertions passed\n";
