<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';

use Acbr\AcbrException;
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Csc;

$checks = 0;
function configCheck(bool $condition, string $description): void
{
    global $checks;
    if (!$condition) { throw new RuntimeException($description); }
    ++$checks;
}
function configFailure(callable $operation, string $code): AcbrException
{
    try { $operation(); }
    catch (AcbrException $error) {
        configCheck($error->codigo === $code, 'Expected ' . $code . ', got ' . $error->codigo);
        return $error;
    }
    throw new RuntimeException('Expected failure: ' . $code);
}

// Deliberately invalid PFX bytes: format/password/CNPJ/validity are native responsibilities.
$pfx = "SYNTHETIC-PFX-SECRET\0\xFF";
$password = ' synthetic senha secreta ç ';
$token = 'CSC-SYNTHETIC-PRIVATE-TOKEN';
$certificate = CertificateA1::fromData($pfx, $password);
$csc = new Csc('000001', $token);
$config = new Configuration('11222333000181', 'CE', modelo:65, certificado:$certificate, csc:$csc);
configCheck($config->hasCertificate() && $config->hasCsc(), 'Configured capability flags');
configCheck($config->toWire() === ['cnpj'=>'11222333000181','uf'=>'CE','modelo'=>65,'ambiente'=>2,
    'certificado'=>['pfxBase64'=>base64_encode($pfx),'senha'=>$password], 'csc'=>['id'=>'000001','valor'=>$token]], 'Exact native config wire contract');
configCheck((new Configuration('11222333000181','CE'))->toWire() === ['cnpj'=>'11222333000181','uf'=>'CE','modelo'=>55,'ambiente'=>2], 'Backwards-compatible configuration defaults');
configCheck(!(new Configuration('11222333000181','CE'))->hasCertificate(), 'Certificate optional for offline generation/validation');
foreach ([$certificate, $csc, $config] as $value) {
    ob_start(); var_dump($value); $debug = ob_get_clean();
    $rendered = json_encode($value, JSON_THROW_ON_ERROR) . $debug . print_r($value, true);
    foreach ([$pfx, base64_encode($pfx), $password, $token, '11222333000181'] as $secret) {
        configCheck(!str_contains($rendered, $secret), 'JSON/debug omit secrets');
    }
    configFailure(fn () => serialize($value), 'SEGREDO_NAO_SERIALIZAVEL');
}
foreach ([['','x'], ['1234567','x'], ['abc','x'], ["1\n",'x'], ['1',''], ['1',str_repeat('x',257)], ['1',"\xFF"]] as [$id,$value]) {
    configFailure(fn () => new Csc($id,$value), 'CSC_INVALIDO');
}
configCheck((new Csc('999999',str_repeat('x',256)))->toWire()['id'] === '999999', 'Maximum CSC boundaries');
configCheck((new Csc('1',str_repeat('ç',128)))->toWire()['valor'] === str_repeat('ç',128), 'Native Unicode BMP byte boundary');
configCheck((new Csc('1',str_repeat('🔒',64)))->toWire()['valor'] === str_repeat('🔒',64), 'Native Unicode astral byte boundary');
configFailure(fn () => new Csc('1',str_repeat('ç',129)), 'CSC_INVALIDO');
configFailure(fn () => new Csc('1',str_repeat('ç',256)), 'CSC_INVALIDO');
configFailure(fn () => new Csc('1',str_repeat('🔒',65)), 'CSC_INVALIDO');
configFailure(fn () => CertificateA1::fromData('', $password), 'CERTIFICADO_INVALIDO');
configFailure(fn () => CertificateA1::fromData(str_repeat('x',CertificateA1::MAX_BYTES+1), $password), 'CERTIFICADO_EXCESSIVO');
configFailure(fn () => CertificateA1::fromData('data', "\xFF"), 'CERTIFICADO_INVALIDO');
configCheck(strlen(base64_decode(CertificateA1::fromData(str_repeat('x',CertificateA1::MAX_BYTES),'')->toWire()['pfxBase64'],true)) === CertificateA1::MAX_BYTES, 'Exact 2 MiB and empty password accepted');
foreach ([['bad','CE',55,2], ['11222333000181','XX',55,2], ['11222333000181','CE',66,2], ['11222333000181','CE',55,3]] as $args) {
    configFailure(fn () => new Configuration(...$args), 'CONFIGURACAO_INVALIDA');
}

$temporary = sys_get_temp_dir() . '/acbr-php-config-test-' . bin2hex(random_bytes(8));
mkdir($temporary,0700);
try {
    $path = $temporary . '/synthetic.pfx';
    file_put_contents($path,$pfx); chmod($path,0600);
    $fileCertificate = CertificateA1::fromFile($path,$password);
    configCheck($fileCertificate->toWire() === $certificate->toWire(), 'File and binary input agree');
    unlink($path);
    configCheck($fileCertificate->toWire() === $certificate->toWire(), 'File snapshotted once, no later path lookup');
    foreach ([$path, $temporary, '', "bad\0path", 'data://text/plain,bad', 'file://' . $path, 'https://example.invalid/a1.pfx', 'php://memory'] as $invalid) {
        configFailure(fn () => CertificateA1::fromFile($invalid,$password), 'CERTIFICADO_AUSENTE');
    }
    file_put_contents($path,str_repeat('x',CertificateA1::MAX_BYTES+1));
    configFailure(fn () => CertificateA1::fromFile($path,$password), 'CERTIFICADO_EXCESSIVO');
    file_put_contents($path,'');
    configFailure(fn () => CertificateA1::fromFile($path,$password), 'CERTIFICADO_INVALIDO');
    if (function_exists('posix_mkfifo')) {
        $fifo = $temporary . '/fifo'; posix_mkfifo($fifo,0600);
        configFailure(fn () => CertificateA1::fromFile($fifo,$password), 'CERTIFICADO_AUSENTE');
        unlink($fifo);
    }
} finally {
    foreach (glob($temporary . '/*') as $file) { unlink($file); }
    rmdir($temporary);
}

// Check actual trace arguments even when PHP is configured to retain arguments.
$previous = ini_set('zend.exception_ignore_args','0');
try {
    try { CertificateA1::fromData('', $password); }
    catch (AcbrException $error) {
        $args = $error->getTrace()[0]['args'];
        configCheck($args[0] instanceof SensitiveParameterValue && $args[1] instanceof SensitiveParameterValue, 'PFX and password trace arguments redacted');
        configCheck(!str_contains($error->getTraceAsString(),$password), 'Password absent from trace text');
    }
    try { new Csc('', $token); }
    catch (AcbrException $error) {
        $args = $error->getTrace()[0]['args'];
        configCheck($args[0] instanceof SensitiveParameterValue && $args[1] instanceof SensitiveParameterValue, 'CSC trace arguments redacted');
    }
} finally { if ($previous !== false) { ini_set('zend.exception_ignore_args',$previous); } }
echo "Configuration/A1/CSC: {$checks} assertions passed\n";
