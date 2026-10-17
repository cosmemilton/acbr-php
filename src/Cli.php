<?php
declare(strict_types=1);
namespace Acbr;

use Acbr\Model\FieldHelpException;

/** Metadata and runtime CLI. Help never resolves or executes an installed worker. */
final class Cli
{
    private const PACKAGE = 'cosmemilton/acbr-php';

    /** @param list<string> $arguments */
    public static function main(array $arguments): int
    {
        $command = $arguments[0] ?? 'help';
        $args = array_slice($arguments, 1);
        try {
            if (in_array($command, ['--help','-h','help'], true)) {
                if ($args === []) { self::help(); return 0; }
                if ($command === 'help' && count($args) === 1 && self::knownCommand($args[0])) { self::help($args[0]); return 0; }
                return self::syntax();
            }
            if ($command === '--version') {
                if ($args !== []) { return self::syntax(); }
                fwrite(STDOUT, 'acbr-php ' . self::sdkVersion() . "\n"); return 0;
            }
            if (in_array($command, ['describe','ajuda'], true)) { return self::describe($command, $args); }
            if (str_starts_with($command, 'runtime:') && self::knownCommand($command)) {
                if (in_array('--help', $args, true) || in_array('-h', $args, true)) { self::help($command); return 0; }
                return self::runtime($command, $args);
            }
            return self::syntax();
        } catch (\Throwable) {
            fwrite(STDERR, "Falha ao carregar os metadados da CLI. Nenhum motor foi executado.\n"); return 1;
        }
    }

    /** Installed package version in a consumer; the SDK checkout uses its release fallback. */
    public static function sdkVersion(): string
    {
        try {
            if (class_exists(\Composer\InstalledVersions::class) && \Composer\InstalledVersions::isInstalled(self::PACKAGE)) {
                $root = \Composer\InstalledVersions::getRootPackage();
                if (($root['name'] ?? null) !== self::PACKAGE) {
                    $matches = true;
                    if (method_exists(\Composer\InstalledVersions::class, 'getInstallPath')) {
                        $path = \Composer\InstalledVersions::getInstallPath(self::PACKAGE);
                        $matches = is_string($path) && realpath($path) === realpath(dirname(__DIR__));
                    }
                    $version = \Composer\InstalledVersions::getPrettyVersion(self::PACKAGE);
                    if ($matches && is_string($version) && preg_match('/\A[A-Za-z0-9][A-Za-z0-9.+_\/-]{0,127}\z/D', $version) === 1) { return preg_replace('/^v(?=[0-9])/', '', $version); }
                }
            }
        } catch (\Throwable) { /* A checkout also works without Composer's installed metadata. */ }
        $bytes = @file_get_contents(dirname(__DIR__) . '/resources/sdk-version.json');
        if (!is_string($bytes)) { throw new \RuntimeException('Versão do SDK indisponível.'); }
        $metadata = json_decode($bytes, true, 8, JSON_THROW_ON_ERROR);
        $version = is_array($metadata) ? ($metadata['version'] ?? null) : null;
        if (($metadata['formatVersion'] ?? null) !== 1 || !is_string($version) || preg_match('/\A[0-9]+\.[0-9]+\.[0-9]+(?:-[0-9A-Za-z.-]+)?\z/D', $version) !== 1) { throw new \RuntimeException('Versão do SDK inválida.'); }
        return $version;
    }

    private static function knownCommand(string $command): bool
    {
        return in_array($command, ['describe','ajuda','runtime:release','runtime:verify','runtime:diagnose','runtime:install'], true);
    }
    private static function syntax(): int
    {
        fwrite(STDERR, "Uso inválido. Execute acbr-php --help.\n"); return 2;
    }
    private static function json(array $value): void
    {
        fwrite(STDOUT, json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
    }
    private static function help(?string $command = null): void
    {
        if ($command === 'describe' || $command === 'ajuda') {
            fwrite(STDOUT, $command === 'describe'
                ? "Uso: acbr-php describe [TARGET] [--json]\n\nDescreve um campo ou tipo do contrato ACBr. TARGET padrão: TNFe.\n--json pode vir antes ou depois de TARGET; a saída padrão é texto em português.\n"
                : "Uso: acbr-php ajuda [TARGET]\n\nConsulta de campo ou tipo em JSON. TARGET padrão: TNFe.\nEste alias sempre retorna JSON e não aceita --json.\n");
            fwrite(STDOUT, "Use --help ou -h para esta ajuda.\n\nExemplos:\n  acbr-php describe Ide.tpNF\n  acbr-php describe TTipoNFe --json\n  acbr-php describe 'Det[0].Prod.vProd'\n  acbr-php ajuda 'Det[].Prod.vProd'\n\nÍndices numéricos são normalizados para []. Nomes distinguem maiúsculas de minúsculas.\nMetadados não determinam obrigatoriedade nem regras fiscais.\nEsta consulta não exige runtime, certificado ou rede e não executa o motor.\n");
            return;
        }
        $runtimeUsage = [
            'runtime:release'=>'runtime:release',
            'runtime:verify'=>'runtime:verify [diretório]',
            'runtime:diagnose'=>'runtime:diagnose [diretório]',
            'runtime:install'=>'runtime:install <arquivo.tgz|diretório-verificado> [destino]',
        ];
        if ($command !== null) {
            fwrite(STDOUT, 'Uso: acbr-php ' . $runtimeUsage[$command] . "\n\n");
            fwrite(STDOUT, match ($command) {
                'runtime:release'=>"Mostra em JSON os pins da distribuição nativa, sem exigir runtime instalado.\n",
                'runtime:verify'=>"Verifica manifesto, hashes e inventário. Retorna JSON; não executa o motor.\n",
                'runtime:diagnose'=>"Mostra diagnóstico JSON da plataforma e do runtime, sem executar o motor.\n",
                default=>"Instala offline o runtime fixado. Não faz downloads e não sobrescreve destinos existentes.\nA fonte pode ser um .tgz oficial ou um diretório verificado; zlib é necessária para .tgz.\n",
            });
            fwrite(STDOUT, "Use --help ou -h para esta ajuda.\nA resolução usa o diretório informado, ACBR_RUNTIME_DIR ou runtime/linux-x64 do pacote.\nNenhum destes comandos executa o motor ou acessa a SEFAZ.\n"); return;
        }
        fwrite(STDOUT, 'ACBr PHP — SDK ' . self::sdkVersion() . "\nRuntime nativo: Linux x64/glibc; consulte a versão fixada com runtime:release.\n\nUso:\n  acbr-php --help\n  acbr-php --version\n  acbr-php describe [TARGET] [--json]\n  acbr-php ajuda [TARGET]\n  acbr-php runtime:release\n  acbr-php runtime:verify [diretório]\n  acbr-php runtime:diagnose [diretório]\n  acbr-php runtime:install <arquivo.tgz|diretório-verificado> [destino]\n\nUse acbr-php <comando> --help para a ajuda específica.\nDescribe e ajuda consultam apenas o contrato embarcado, sem runtime, A1 ou rede.\nO SDK e o runtime têm versões independentes; --version informa a versão do SDK.\nA instalação é offline, verifica SHA-256 e inventário e preserva destinos existentes.\nOs comandos runtime:* conservam saída JSON e não executam o motor nem acessam a SEFAZ.\n");
    }

    private static function describe(string $command, array $arguments): int
    {
        $beforeEnd = array_slice($arguments, 0, array_search('--', $arguments, true) === false ? count($arguments) : (int)array_search('--', $arguments, true));
        if (in_array('--help', $beforeEnd, true) || in_array('-h', $beforeEnd, true)) { self::help($command); return 0; }
        $json = $command === 'ajuda'; $target = null; $endOptions = false;
        foreach ($arguments as $argument) {
            if (!$endOptions && $argument === '--') { $endOptions = true; continue; }
            if (!$endOptions && $argument === '--json' && $command === 'describe') { $json = true; continue; }
            if ((!$endOptions && str_starts_with($argument, '-')) || $target !== null) { return self::syntax(); }
            $target = $argument;
        }
        $sdkVersion = 'desconhecida';
        try {
            $sdkVersion = self::sdkVersion();
            $description = Contract::bundled()->help($target ?? 'TNFe') + ['sdkVersion'=>$sdkVersion];
            if ($json) { self::json($description); }
            else { fwrite(STDOUT, self::formatDescription($description) . "\n"); }
            return 0;
        } catch (FieldHelpException $error) {
            return self::queryFailure($json, $error->codigo, $error->getMessage(), $error->candidates, $sdkVersion);
        } catch (\Throwable) {
            return self::queryFailure($json, 'AJUDA_INDISPONIVEL', 'Os metadados do contrato estão indisponíveis ou inválidos.', [], $sdkVersion);
        }
    }
    private static function queryFailure(bool $json, string $code, string $message, array $candidates, string $sdkVersion): int
    {
        if ($json) { self::json(['ok'=>false,'codigo'=>$code,'mensagem'=>$message,'candidates'=>$candidates,'sdkVersion'=>$sdkVersion]); }
        else {
            fwrite(STDERR, 'Erro [' . $code . ']: ' . $message . "\n");
            if ($candidates !== []) { fwrite(STDERR, "Candidatos:\n  - " . implode("\n  - ", $candidates) . "\n"); }
            fwrite(STDERR, "Ação: use o caminho completo e consulte acbr-php describe --help.\n");
        }
        return 1;
    }
    private static function formatDescription(array $help): string
    {
        $lines = [$help['path'], 'Tipo: ' . $help['pascalType'] . ' (' . $help['kind'] . '; entrada ' . $help['inputType'] . ')', 'Somente leitura: ' . ($help['readonly'] ? 'sim' : 'não')];
        if (isset($help['values'])) { $lines[] = 'Valores: ' . implode(', ', $help['values']); }
        if (array_key_exists('declaredDefault', $help)) { $lines[] = 'Padrão declarado: ' . (is_string($help['declaredDefault']) ? $help['declaredDefault'] : json_encode($help['declaredDefault'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)); }
        if (isset($help['constraints'])) { $lines[] = 'Limites: ' . json_encode($help['constraints'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR); }
        if (isset($help['itemType'])) { $lines[] = 'Tipo do elemento: ' . $help['itemType']; }
        if (isset($help['members'])) {
            $lines[] = 'Campos:';
            foreach ($help['members'] as $member) { $lines[] = '  - ' . $member['name'] . ': ' . $member['pascalType'] . ' (' . $member['kind'] . '; somente leitura: ' . ($member['readonly'] ? 'sim' : 'não') . ')'; }
        }
        $lines[] = 'Revisão ACBr: ' . $help['sourceRevision']; $lines[] = 'Contrato: ' . $help['contractHash']; $lines[] = 'SDK PHP: ' . $help['sdkVersion'];
        return implode("\n", $lines);
    }

    /** Keep the established runtime JSON, error channels and exit statuses unchanged. */
    private static function runtime(string $command, array $arguments): int
    {
        try {
            if ($command === 'runtime:release' && count($arguments) === 0) { self::json(NativeRuntime::release()); }
            elseif ($command === 'runtime:diagnose' && count($arguments) <= 1) {
                $diagnostics = NativeRuntime::diagnostics($arguments[0] ?? null); self::json($diagnostics); return $diagnostics['ok'] ? 0 : 1;
            } elseif ($command === 'runtime:verify' && count($arguments) <= 1) {
                $runtime = NativeRuntime::resolve($arguments[0] ?? null);
                self::json(['ok'=>true,'directory'=>$runtime->directory,'executable'=>$runtime->executable,'verifiedFiles'=>count($runtime->manifest['files']),
                    'sourceRevision'=>NativeRuntime::SOURCE_REVISION,'contractHash'=>NativeRuntime::CONTRACT_HASH,'nativeExecutionChecked'=>false]);
            } elseif ($command === 'runtime:install' && count($arguments) >= 1 && count($arguments) <= 2) {
                $runtime = NativeRuntime::install($arguments[0], $arguments[1] ?? null);
                self::json(['ok'=>true,'directory'=>$runtime->directory,'verifiedFiles'=>count($runtime->manifest['files']),'nativeExecutionChecked'=>false]);
            } else { return self::syntax(); }
            return 0;
        } catch (AcbrException $error) {
            fwrite(STDERR, json_encode(['ok'=>false,'codigo'=>$error->codigo,'mensagem'=>$error->getMessage()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n"); return 1;
        } catch (\Throwable) {
            fwrite(STDERR, "Falha interna ao verificar ou instalar o runtime. Nenhum motor foi executado.\n"); return 1;
        }
    }
}
