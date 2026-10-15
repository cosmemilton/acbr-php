<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

use Acbr\AcbrException;
use Acbr\Async\OperationQueue;
use Acbr\NativeRuntime;

$directory = getenv('ACBR_TEST_RUNTIME') ?: getenv('ACBR_RUNTIME_DIR');
if (!$directory) {
    echo "SKIP native async parent-death test: set ACBR_TEST_RUNTIME or ACBR_RUNTIME_DIR.\n";
    exit(0);
}
if (PHP_OS_FAMILY !== 'Linux' || !function_exists('pcntl_fork') || !function_exists('posix_kill')) {
    echo "SKIP native async parent-death test: optional Linux CLI backend required.\n";
    exit(0);
}
$runtime = NativeRuntime::resolve($directory);
$path = sys_get_temp_dir() . '/acbr-php-pdeath-' . bin2hex(random_bytes(8));
$queue = new OperationQueue(1, 5000);
$stdinPair = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
if ($stdinPair === false) { throw new RuntimeException('Cannot create test stdin socket pair.'); }
$wrapperPid = null; $nativePid = null; $fallbackAnchored = false;
$count = 0;
$check = static function (bool $condition, string $name) use (&$count): void {
    if (!$condition) { throw new RuntimeException('FAILED: ' . $name); }
    $count++;
};
$state = static function (int $pid): ?string {
    $stat = @file_get_contents('/proc/' . $pid . '/stat');
    if ($stat === false) { return null; }
    $end = strrpos($stat, ')');
    return $end === false ? null : explode(' ', trim(substr($stat, $end + 1)))[0];
};
$dead = static fn(int $pid): bool => in_array($state($pid), [null, 'Z', 'X'], true);
try {
    $future = $queue->submit(static function () use ($runtime, $path, $stdinPair): never {
        // Deliberately send NO JSON and keep stdin open. This starts the verified
        // native binary only; no certificate, signing, fiscal command or network.
        fclose($stdinPair[0]); // The original parent keeps the writing peer open.
        $process = proc_open([$runtime->executable, '--parent-pid', (string)getmypid()], [0=>$stdinPair[1], 1=>['pipe','w'], 2=>['pipe','w']], $pipes, $runtime->directory, $runtime->environment(), ['bypass_shell'=>true]);
        if (!is_resource($process)) { throw new AcbrException('FALHA_INICIAR_MOTOR', 'Não iniciou.'); }
        $status = proc_get_status($process);
        file_put_contents($path, json_encode(['wrapper'=>getmypid(), 'native'=>$status['pid']], JSON_THROW_ON_ERROR), LOCK_EX);
        usleep(200000);
        // No proc_close(), normal PHP shutdown or queue group kill can help here.
        posix_kill(getmypid(), SIGKILL);
        exit(255);
    }, false);
    fclose($stdinPair[1]);
    $deadline = hrtime(true) + 2000000000;
    while (!is_file($path) && hrtime(true) < $deadline) { usleep(1000); }
    $check(is_file($path), 'native worker launched');
    $ids = json_decode(file_get_contents($path), true, 16, JSON_THROW_ON_ERROR);
    $wrapperPid = $ids['wrapper']; $nativePid = $ids['native']; $fallbackAnchored = true;
    $check($wrapperPid !== getmypid() && $nativePid !== $wrapperPid, 'native is a separate owned descendant');
    $check(!$dead($wrapperPid) && !$dead($nativePid), 'wrapper and native are initially alive');
    $deadline = hrtime(true) + 2000000000;
    while (!$dead($wrapperPid) && hrtime(true) < $deadline) { usleep(1000); }
    $check($dead($wrapperPid), 'PHP wrapper deliberately died');
    while (!$dead($nativePid) && hrtime(true) < $deadline) { usleep(1000); }
    // This is checked BEFORE any queue progress/close call can send a signal.
    $check($dead($nativePid), 'verified native died on parent loss without queue cleanup');
    $check(is_resource($stdinPair[0]), 'stdin peer remains open: no EOF could explain native exit');
    $fallbackAnchored = false; // await/queue cleanup can reap and release the wrapper PID.
    try { $future->await(); throw new RuntimeException('Expected abrupt-child failure'); }
    catch (AcbrException $error) { $check(in_array($error->codigo, ['FALHA_ASYNC','FALHA_LIMPEZA_ASYNC'], true), 'abrupt wrapper loss reported'); }
    $check($dead($nativePid), 'native is still terminated');
    // A container init may retain an orphan zombie. Kernel termination is
    // verified above; group disappearance/reaping is a separate cleanup claim.
    $check(!posix_kill($wrapperPid, 0), 'owned PHP wrapper was reaped');
} finally {
    // On a failed regression, clean only the verified private group while its
    // unreaped wrapper still anchors the PID. Never touch unrelated processes.
    if ($fallbackAnchored && $wrapperPid !== null && $nativePid !== null && !$dead($nativePid) && posix_getpgid($nativePid) === $wrapperPid && $state($wrapperPid) === 'Z') { posix_kill(-$wrapperPid, SIGKILL); }
    try { $queue->close(); } catch (AcbrException $error) { if ($error->limpezaConfirmada) { throw $error; } }
    foreach ($stdinPair as $pipe) { if (is_resource($pipe)) { fclose($pipe); } }
    if (is_file($path)) { unlink($path); }
}
echo "Native async parent-death: {$count} assertions passed; verified worker, no JSON commands, no certificate, no SEFAZ.\n";
