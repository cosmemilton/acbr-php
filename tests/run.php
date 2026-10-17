<?php
declare(strict_types=1);
$files = ['process-test.php', 'contract-test.php', 'runtime-test.php', 'configuration-test.php', 'fiscal-operations-test.php', 'recuperar-xml-test.php', 'async-test.php', 'cli-test.php'];
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (!is_file($path)) { fwrite(STDERR, "Missing test suite: {$file}\n"); exit(1); }
    passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($path), $status);
    if ($status !== 0) { exit($status); }
}
echo "All unit/integration suites passed\n";
