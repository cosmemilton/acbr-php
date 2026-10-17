<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';

use Acbr\Contract;
use Acbr\Model\FieldHelpException;
use Acbr\NativeRuntime;

// Metadata-only CLI tests. No worker, certificate, fiscal request or network.
$count = 0;
$check = static function (bool $ok, string $label) use (&$count): void {
    if (!$ok) { throw new RuntimeException('FAILED: '.$label); }
    ++$count;
};
$root = dirname(__DIR__);
$bin = $root.'/bin/acbr-php';
$version = '0.1.0-alpha2';
$contract = Contract::bundled();
$temporary = sys_get_temp_dir().'/acbr-php-cli-test-'.bin2hex(random_bytes(8));
mkdir($temporary, 0700);
$missing = $temporary.'/absent-runtime';
$marker = $temporary.'/runtime-resolution';
$prepend = $temporary.'/metadata-stubs.php';
file_put_contents($prepend, <<<'STUB'
<?php
namespace Composer {
    final class InstalledVersions {
        public static function isInstalled(string $name, bool $includeDevRequirements = true): bool { return $name === 'cosmemilton/acbr-php'; }
        public static function getRootPackage(): array {
            return ['name'=>str_starts_with((string)getenv('ACBR_CLI_TEST_COMPOSER'), 'consumer') ? 'fixture/consumer' : 'cosmemilton/acbr-php', 'pretty_version'=>'dev-fixture', 'version'=>'dev-fixture'];
        }
        public static function getPrettyVersion(string $name): ?string {
            return match (getenv('ACBR_CLI_TEST_COMPOSER')) {
                'consumer-v'=>'v0.1.0-alpha2',
                'consumer-dev'=>'dev-version-fixture',
                default=>'0.1.0-alpha77',
            };
        }
        public static function getVersion(string $name): ?string { return '0.1.0.0-alpha77'; }
    }
}
namespace Acbr {
    final class NativeRuntime {
        public const SOURCE_REVISION = 48590;
        public const CONTRACT_HASH = 'e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d';
        public static function resolve(...$arguments): never {
            file_put_contents((string)getenv('ACBR_CLI_TEST_MARKER'), 'resolve', FILE_APPEND | LOCK_EX);
            throw new \RuntimeException('SYNTHETIC_FORBIDDEN_RUNTIME_RESOLUTION');
        }
        public static function release(): array {
            return json_decode((string)file_get_contents((string)getenv('ACBR_CLI_TEST_RELEASE')), true, 64, JSON_THROW_ON_ERROR);
        }
        public static function __callStatic(string $name, array $arguments): never {
            file_put_contents((string)getenv('ACBR_CLI_TEST_MARKER'), $name, FILE_APPEND | LOCK_EX);
            throw new \RuntimeException('SYNTHETIC_FORBIDDEN_RUNTIME_OPERATION');
        }
    }
}
STUB
);

/** Execute only the PHP CLI entry point, with bounded pipes and an owned child. */
$call = static function (array $arguments, bool $guardRuntime = true, string $composer = 'checkout') use ($bin, $root, $temporary, $prepend, $marker, $missing): array {
    $command = [PHP_BINARY, '-d', 'log_errors=0', '-d', 'display_errors=stderr'];
    if ($guardRuntime) { array_push($command, '-d', 'auto_prepend_file='.$prepend); }
    array_push($command, $bin, ...$arguments);
    $environment = [
        'PATH'=>dirname(PHP_BINARY).PATH_SEPARATOR.'/usr/local/bin'.PATH_SEPARATOR.'/usr/bin'.PATH_SEPARATOR.'/bin',
        'ACBR_RUNTIME_DIR'=>$missing,
        'ACBR_CLI_TEST_MARKER'=>$marker,
        'ACBR_CLI_TEST_RELEASE'=>$root.'/resources/runtime-release.json',
        'ACBR_CLI_TEST_COMPOSER'=>$composer,
    ];
    if (PHP_OS_FAMILY === 'Windows' && is_string(getenv('SystemRoot'))) { $environment['SystemRoot'] = getenv('SystemRoot'); }
    $process = null; $pipes = []; $stdout = ''; $stderr = ''; $exit = null;
    try {
        $process = proc_open($command, [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes, $temporary, $environment, ['bypass_shell'=>true]);
        if (!is_resource($process)) { throw new RuntimeException('CLI_TEST_PROCESS_UNAVAILABLE'); }
        fclose($pipes[0]); unset($pipes[0]);
        stream_set_blocking($pipes[1], false); stream_set_blocking($pipes[2], false);
        $deadline = hrtime(true) + 5000000000;
        while (true) {
            foreach ([1,2] as $fd) {
                $chunk = fread($pipes[$fd], 8192);
                if ($chunk === false) { throw new RuntimeException('CLI_TEST_PIPE_FAILED'); }
                if ($fd === 1) { $stdout .= $chunk; } else { $stderr .= $chunk; }
            }
            if (strlen($stdout) + strlen($stderr) > 1048576) { throw new RuntimeException('CLI_TEST_OUTPUT_EXCESSIVE'); }
            $status = proc_get_status($process);
            if (!$status['running']) {
                $exit ??= $status['exitcode'];
                if (feof($pipes[1]) && feof($pipes[2])) { break; }
            }
            if (hrtime(true) >= $deadline) { throw new RuntimeException('CLI_TEST_TIMEOUT'); }
            usleep(1000);
        }
        return ['exit'=>$exit, 'stdout'=>$stdout, 'stderr'=>$stderr];
    } finally {
        if (is_resource($process) && proc_get_status($process)['running']) {
            proc_terminate($process, 9);
            $deadline = hrtime(true) + 1000000000;
            while (proc_get_status($process)['running'] && hrtime(true) < $deadline) { usleep(1000); }
        }
        foreach ($pipes as $pipe) { if (is_resource($pipe)) { fclose($pipe); } }
        if (is_resource($process)) {
            if (proc_get_status($process)['running']) { throw new RuntimeException('CLI_TEST_CLEANUP_UNCONFIRMED'); }
            proc_close($process);
        }
    }
};
$json = static function (array $result, string $channel = 'stdout'): array {
    try { $value = json_decode($result[$channel], true, 128, JSON_THROW_ON_ERROR); }
    catch (JsonException) { throw new RuntimeException('CLI_TEST_INVALID_JSON'); }
    if (!is_array($value)) { throw new RuntimeException('CLI_TEST_JSON_OBJECT_EXPECTED'); }
    return $value;
};
$assertJsonHelp = static function (array $arguments, string $query) use ($call, $json, $check, $contract, $version): array {
    $result = $call($arguments);
    $check($result['exit'] === 0 && $result['stderr'] === '', 'JSON help exits successfully without stderr');
    $value = $json($result);
    $check($value == $contract->help($query) + ['sdkVersion'=>$version], 'JSON help equals contract metadata plus SDK version');
    return $value;
};

try {
    foreach ([[], ['--help'], ['-h'], ['help']] as $arguments) {
        $result = $call($arguments);
        $check($result['exit'] === 0 && $result['stderr'] === '', 'global help channels and exit');
        foreach (['describe', 'ajuda', 'runtime:release', 'runtime:verify', 'runtime:diagnose', 'runtime:install'] as $name) {
            $check(str_contains($result['stdout'], $name), 'global help exposes supported commands');
        }
    }
    foreach (['describe', 'ajuda', 'runtime:release', 'runtime:verify', 'runtime:diagnose', 'runtime:install'] as $command) {
        foreach (['--help', '-h'] as $flag) {
            $result = $call([$command, $flag]);
            $check($result['exit'] === 0 && $result['stderr'] === '' && str_contains($result['stdout'], $command), 'command-specific help is offline');
        }
        $result = $call(['help', $command]);
        $check($result['exit'] === 0 && $result['stderr'] === '' && str_contains($result['stdout'], $command), 'help command selects command-specific usage');
    }
    $result = $call(['describe', '--help', 'extra']);
    $check($result['exit'] === 0 && $result['stderr'] === '' && str_contains($result['stdout'], 'describe'), 'help flag precedes query parsing');
    $result = $call(['--version']);
    $check($result === ['exit'=>0, 'stdout'=>'acbr-php '.$version."\n", 'stderr'=>''], 'checkout version uses resource instead of Composer root version');
    $result = $call(['--version'], composer:'consumer');
    $check($result === ['exit'=>0, 'stdout'=>"acbr-php 0.1.0-alpha77\n", 'stderr'=>''], 'consumer version comes from Composer InstalledVersions');
    $result = $call(['--version'], composer:'consumer-v');
    $check($result === ['exit'=>0, 'stdout'=>"acbr-php 0.1.0-alpha2\n", 'stderr'=>''], 'consumer version drops only the numeric tag prefix');
    $result = $call(['--version'], composer:'consumer-dev');
    $check($result === ['exit'=>0, 'stdout'=>"acbr-php dev-version-fixture\n", 'stderr'=>''], 'consumer development branch version stays intact');
    $result = $call(['describe', '--json', 'TIde'], composer:'consumer');
    $check($result['exit'] === 0 && ($json($result)['sdkVersion'] ?? null) === '0.1.0-alpha77', 'consumer SDK version is also present in JSON metadata');

    foreach (['TNFe', 'TIde', 'Ide.tpAmb', 'TACBrTipoAmbiente', 'Det', 'TNFe.Det[]', 'Det[12].Prod.vProd', 'signature.DigestValue'] as $query) {
        $value = $assertJsonHelp(['describe', '--json', $query], $query);
        $check($assertJsonHelp(['describe', $query, '--json'], $query) === $value, 'JSON flag order does not alter output');
        $check($assertJsonHelp(['ajuda', $query], $query) === $value, 'ajuda remains the JSON alias');
    }
    $assertJsonHelp(['describe', '--json'], 'TNFe');
    $assertJsonHelp(['describe', '--json', '--json'], 'TNFe');
    $assertJsonHelp(['describe', '--json', '--', 'TIde'], 'TIde');
    $assertJsonHelp(['ajuda'], 'TNFe');
    $field = $contract->help('Ide.tpAmb');
    $check(isset($field['declaredDefault']) && !array_key_exists('declaredDefault', $contract->help('TACBrTipoAmbiente')), 'default belongs to the enum field, not the enum type');
    $check($contract->help('Det[12].Prod.vProd')['path'] === 'TNFe.Det[].Prod.vProd', 'numeric index is normalized for metadata help');
    $check($contract->help('signature.DigestValue')['readonly'] === true, 'readonly ancestor is preserved');
    $check(is_array($contract->help('TIde')['members']) && $contract->help('Det')['itemType'] === 'TDetCollectionItem', 'object and list metadata remain structured');
    $result = $call(['describe']);
    $check($result['exit'] === 0 && $result['stderr'] === '' && str_starts_with($result['stdout'], 'TNFe'), 'describe defaults to TNFe text');
    $check(str_contains($result['stdout'], 'Tipo:') && str_contains($result['stdout'], 'Somente leitura:'), 'describe text is Portuguese');
    $result = $call(['describe', 'Ide.tpAmb']);
    $check($result['exit'] === 0 && str_contains($result['stdout'], 'Valores:') && str_contains($result['stdout'], 'Padrão declarado:'), 'enum field text includes values and declared default');
    $result = $call(['describe', 'TACBrTipoAmbiente']);
    $check($result['exit'] === 0 && !str_contains($result['stdout'], 'Padrão declarado:'), 'enum type text does not invent a default');

    foreach (['CNPJCPF', 'Ide.doesNotExist', 'Det[-1]', ' TIde ', 'ide.tpAmb'] as $query) {
        try { $contract->help($query); throw new RuntimeException('CLI_TEST_INVALID_ERROR_FIXTURE'); }
        catch (FieldHelpException $expected) {}
        $result = $call(['describe', $query]);
        $check($result['exit'] === 1 && $result['stdout'] === '' && str_contains($result['stderr'], $expected->codigo), 'text query errors use stderr and exit one');
        foreach ([['describe', '--json', $query], ['describe', $query, '--json'], ['ajuda', $query]] as $arguments) {
            $result = $call($arguments);
            $value = $json($result);
            $check($result['exit'] === 1 && $result['stderr'] === '', 'JSON query errors use stdout and exit one');
            $check(($value['ok'] ?? null) === false && ($value['codigo'] ?? null) === $expected->codigo && ($value['sdkVersion'] ?? null) === $version, 'JSON query diagnostic has typed status code and SDK version');
            $check(($value['candidates'] ?? null) === $expected->candidates && is_string($value['mensagem'] ?? null) && $value['mensagem'] !== '', 'JSON query candidates match static contract metadata');
        }
    }
    $privateQuery = 'SYNTHETIC_QUERY_SECRET_SENTINEL[]';
    $result = $call(['describe', '--json', $privateQuery]);
    $check($result['exit'] === 1 && !str_contains($result['stdout'].$result['stderr'], $privateQuery), 'invalid user query is not reflected in diagnostics');
    $result = $call(['describe', '--', '--help']);
    $check($result['exit'] === 1 && $result['stdout'] === '' && str_contains($result['stderr'], 'CAMINHO_INVALIDO'), 'help flag after end-options is treated as an invalid query');

    foreach ([
        ['describe', 'TNFe', 'TIde'], ['describe', '--unknown'],
        ['describe', '--json', 'TNFe', 'extra'],
        ['ajuda', '--json'], ['ajuda', 'TNFe', '--json'], ['ajuda', 'TNFe', 'TIde'],
        ['--version', 'extra'], ['--help', 'extra'], ['help', 'extra'], ['--json'], ['unknown-command'],
        ['runtime:release', 'extra'], ['runtime:verify', 'a', 'b'],
        ['runtime:diagnose', 'a', 'b'], ['runtime:install'], ['runtime:install', 'a', 'b', 'c'],
    ] as $arguments) {
        $result = $call($arguments);
        $check($result['exit'] === 2 && $result['stdout'] === '' && $result['stderr'] !== '', 'syntax errors use stderr and exit two');
    }
    $check(!file_exists($marker), 'help version description and syntax never resolve a runtime or execute a runtime operation');

    // These legacy commands only read metadata or reject a nonexistent runtime.
    $result = $call(['runtime:release'], guardRuntime:false);
    $check($result['exit'] === 0 && $result['stderr'] === '' && $json($result) == NativeRuntime::release(), 'legacy runtime release JSON is unchanged');
    $result = $call(['runtime:verify', $missing], guardRuntime:false);
    $failure = $json($result, 'stderr');
    $keys = array_keys($failure); sort($keys);
    $check($result['exit'] === 1 && $result['stdout'] === '' && $keys === ['codigo','mensagem','ok'] && $failure['ok'] === false, 'legacy runtime verify error JSON remains on stderr');
    $result = $call(['runtime:diagnose', $missing], guardRuntime:false);
    $diagnostic = $json($result);
    $check($result['exit'] === 1 && $result['stderr'] === '' && ($diagnostic['ok'] ?? null) === false && ($diagnostic['nativeExecutionChecked'] ?? null) === false, 'legacy runtime diagnose failure stays JSON on stdout without worker execution');
} finally {
    foreach ([$prepend, $marker] as $path) { if (is_file($path)) { unlink($path); } }
    rmdir($temporary);
}
echo "CLI metadata: {$count} assertions passed; no native worker, certificates or network\n";
