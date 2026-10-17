<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';

use Acbr\AcbrException;
use Acbr\NativeRuntime;

$checks = 0;
function runtimeCheck(bool $condition, string $description): void
{
    global $checks;
    if (!$condition) {
        throw new RuntimeException($description);
    }
    ++$checks;
}
function runtimeFailure(callable $operation, string $code): void
{
    try {
        $operation();
    } catch (AcbrException $error) {
        runtimeCheck($error->codigo === $code, 'Expected ' . $code . ', got ' . $error->codigo);
        return;
    }
    throw new RuntimeException('Expected failure: ' . $code);
}
function runtimeRemove(string $path): void
{
    if (is_dir($path) && !is_link($path)) {
        foreach (new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS) as $entry) {
            runtimeRemove($entry->getPathname());
        }
        rmdir($path);
    } else {
        unlink($path);
    }
}

$temporary = sys_get_temp_dir() . '/acbr-php-runtime-test-' . bin2hex(random_bytes(8));
mkdir($temporary, 0700);
try {
    $release = NativeRuntime::release();
    runtimeCheck($release['sourceRevision'] === 48590, 'Exact source revision');
    runtimeCheck($release['version'] === '0.2.1', 'Exact runtime release');
    runtimeCheck($release['capabilities'] === ['recuperarXml'], 'Pinned recovery capability');
    runtimeCheck($release['manifestSha256'] === 'd7e0bb150982daa3670d1ee8d6a1e0e1e422b2e213bc03c6ea8828ffea659414', 'Exact release manifest pin');
    runtimeFailure(fn () => NativeRuntime::resolve($temporary . '/absent'), 'RUNTIME_AUSENTE');
    foreach (['../runtime', 'https://example.invalid/runtime', "bad\0path", 'C:\\runtime'] as $path) {
        runtimeFailure(fn () => NativeRuntime::resolve($path), 'RUNTIME_CAMINHO_INVALIDO');
    }
    $fake = $temporary . '/fake';
    mkdir($fake);
    file_put_contents($fake . '/manifest.json', '{"formatVersion":1}');
    runtimeFailure(fn () => NativeRuntime::resolve($fake), 'RUNTIME_INVALIDO');
    $manifest = ['formatVersion' => 1, 'sourceRevision' => 48590, 'contractHash' => NativeRuntime::CONTRACT_HASH,
        'platform' => 'linux', 'arch' => 'x64', 'executable' => 'acbr-worker', 'files' => (object) ['acbr-worker' => str_repeat('0', 64)]];
    file_put_contents($fake . '/manifest.json', json_encode($manifest));
    runtimeFailure(fn () => NativeRuntime::resolve($fake), 'RUNTIME_ALTERADO');
    $manifest['sourceRevision'] = 1;
    file_put_contents($fake . '/manifest.json', json_encode($manifest));
    runtimeFailure(fn () => NativeRuntime::resolve($fake), 'CONTRATO_DIVERGENTE');
    file_put_contents($temporary . '/untrusted.tgz', 'never extract or run me');
    runtimeFailure(fn () => NativeRuntime::install($temporary . '/untrusted.tgz', $temporary . '/never-created'), 'RUNTIME_ALTERADO');
    runtimeCheck(!file_exists($temporary . '/never-created'), 'Untrusted archive leaves no installation');
    symlink($fake, $temporary . '/linked-root');
    runtimeFailure(fn () => NativeRuntime::resolve($temporary . '/linked-root'), 'RUNTIME_LINK_INVALIDO');
    symlink($temporary, $temporary . '/linked-parent');
    runtimeFailure(fn () => NativeRuntime::resolve($temporary . '/linked-parent/fake'), 'RUNTIME_LINK_INVALIDO');
    unlink($fake . '/manifest.json');
    file_put_contents($temporary . '/manifest.json', '{}');
    symlink($temporary . '/manifest.json', $fake . '/manifest.json');
    runtimeFailure(fn () => NativeRuntime::resolve($fake), 'RUNTIME_LINK_INVALIDO');
    $relative = new ReflectionMethod(NativeRuntime::class, 'relativePath');
    foreach (['../worker', '/worker', 'lib/../../worker', 'lib//file', './worker', 'lib/./worker', 'lib\\file', "lib/\0file", 'C:/worker'] as $path) {
        runtimeFailure(fn () => $relative->invoke(null, $path), 'RUNTIME_CAMINHO_INVALIDO');
    }
    $diagnostics = NativeRuntime::diagnostics($temporary . '/absent');
    runtimeCheck($diagnostics['ok'] === false && $diagnostics['nativeExecutionChecked'] === false, 'Diagnostics never launch native code');

    // Extraction guard tests call the private parser directly; production always checks the pinned archive first.
    $extract = new ReflectionMethod(NativeRuntime::class, 'extractPinnedArchive');
    if (function_exists('gzencode')) {
        foreach ([['package/runtime/../../outside', '0', ''], ['package/runtime/link', '2', '/tmp/outside'],
            ['package/runtime/hardlink', '1', 'package/runtime/acbr-worker'], ['package/runtime/device', '3', '']] as [$name, $type, $link]) {
            $header = str_pad($name, 100, "\0") . sprintf('%07o', 0644) . "\0" . str_repeat("0000000\0", 2)
                . sprintf('%011o', 0) . "\0" . sprintf('%011o', 0) . "\0" . str_repeat(' ', 8) . $type
                . str_pad($link, 100, "\0") . "ustar\00000";
            $header = str_pad($header, 512, "\0");
            $header = substr_replace($header, sprintf('%06o', array_sum(unpack('C*', $header))) . "\0 ", 148, 8);
            $archive = $temporary . '/malicious-' . bin2hex(random_bytes(4)) . '.tgz';
            file_put_contents($archive, gzencode($header . str_repeat("\0", 1024)));
            runtimeFailure(fn () => $extract->invoke(null, $archive, $fake), $type === '0' ? 'RUNTIME_CAMINHO_INVALIDO' : 'RUNTIME_ARQUIVO_INVALIDO');
        }
    }

    $source = getenv('ACBR_TEST_RUNTIME') ?: getenv('ACBR_RUNTIME_DIR');
    $archive = getenv('ACBR_TEST_RUNTIME_ARCHIVE');
    if (is_string($archive) && $archive !== '') {
        $installedArchive = NativeRuntime::install($archive, $temporary . '/from-archive');
        runtimeCheck(count($installedArchive->manifest['files']) === 264, 'Verified tar installation inventory');
        $source = $installedArchive->directory;
    }
    if (is_string($source) && $source !== '') {
        $runtime = NativeRuntime::resolve($source);
        runtimeCheck($runtime->root === $runtime->directory, 'Root alias');
        runtimeFailure(fn () => NativeRuntime::install($source, $source . '/must-not-create/child'), 'RUNTIME_CAMINHO_INVALIDO');
        runtimeCheck(!file_exists($source . '/must-not-create'), 'Nested destination leaves source untouched');
        putenv('LD_PRELOAD=/untrusted/evil.so');
        putenv('ACBR_TEST_FAKE_SECRET=must-not-leak');
        $runtime = NativeRuntime::resolve($source);
        runtimeCheck(!isset($runtime->environment['LD_PRELOAD']) && !isset($runtime->environment['ACBR_TEST_FAKE_SECRET']), 'Dangerous loader variables and secrets excluded');
        runtimeCheck($runtime->environment()['LD_LIBRARY_PATH'] === $runtime->directory . '/lib', 'Pinned library directory');
        putenv('LD_PRELOAD');
        putenv('ACBR_TEST_FAKE_SECRET');
        $copy = NativeRuntime::install($source, $temporary . '/installed');
        runtimeCheck(count($copy->manifest['files']) === 264, 'Verified directory installation inventory');
        runtimeFailure(fn () => NativeRuntime::install($source, $copy->directory), 'RUNTIME_DESTINO_EXISTENTE');
        file_put_contents($copy->directory . '/openssl.cnf', "\n; tampering\n", FILE_APPEND);
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_ALTERADO');
        copy($source . '/openssl.cnf', $copy->directory . '/openssl.cnf');
        file_put_contents($copy->directory . '/lib/extra.so', 'untrusted library');
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_ARQUIVO_EXTRA');
        unlink($copy->directory . '/lib/extra.so');
        unlink($copy->directory . '/openssl.cnf');
        symlink($source . '/openssl.cnf', $copy->directory . '/openssl.cnf');
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_LINK_INVALIDO');
        unlink($copy->directory . '/openssl.cnf');
        copy($source . '/openssl.cnf', $copy->directory . '/openssl.cnf');
        rename($copy->directory . '/lib', $temporary . '/external-lib');
        symlink($temporary . '/external-lib', $copy->directory . '/lib');
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_LINK_INVALIDO');
        unlink($copy->directory . '/lib');
        rename($temporary . '/external-lib', $copy->directory . '/lib');
        unlink($copy->directory . '/openssl.cnf');
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_INCOMPLETO');
        copy($source . '/openssl.cnf', $copy->directory . '/openssl.cnf');
        chmod($copy->executable, 0644);
        runtimeFailure(fn () => NativeRuntime::resolve($copy->directory), 'RUNTIME_NAO_EXECUTAVEL');
    } else {
        echo "SKIP verified runtime integration: set ACBR_TEST_RUNTIME or ACBR_TEST_RUNTIME_ARCHIVE.\n";
    }
    echo "PASS runtime: {$checks} checks; no native execution, certificates or SEFAZ.\n";
} finally {
    runtimeRemove($temporary);
}
