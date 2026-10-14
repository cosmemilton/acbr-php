<?php
declare(strict_types=1);

namespace Acbr;

/** A fully inventoried, release-pinned Linux x64 runtime. Verification never runs native code. */
final class NativeRuntime
{
    public const SOURCE_REVISION = 48590;
    public const CONTRACT_HASH = 'e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d';
    private const MAX_MANIFEST_BYTES = 1048576;
    private const MAX_TAR_BYTES = 268435456;

    public readonly string $root;
    /** @var array<string,string> */
    public readonly array $environment;

    private function __construct(
        public readonly string $directory,
        public readonly string $executable,
        public readonly array $manifest,
    ) {
        $this->root = $directory;
        $environment = [];
        // Never inherit credentials, LD_PRELOAD, LD_AUDIT, or caller-selected OpenSSL configuration.
        foreach (['PATH', 'HOME', 'LANG', 'LC_ALL', 'TMPDIR', 'TMP', 'TEMP'] as $name) {
            $value = getenv($name);
            if (is_string($value)) {
                $environment[$name] = $value;
            }
        }
        $environment['LD_LIBRARY_PATH'] = $directory . '/lib';
        $environment['OPENSSL_MODULES'] = $directory . '/ossl-modules';
        $environment['OPENSSL_CONF'] = $directory . '/openssl.cnf';
        $this->environment = $environment;
    }

    /** @return array<string,string> */
    public function environment(): array
    {
        return $this->environment;
    }

    public static function defaultDirectory(): string
    {
        return dirname(__DIR__) . '/runtime/linux-x64';
    }

    /** @return array<string,mixed> The distribution's trusted release pin, not runtime-supplied metadata. */
    public static function release(): array
    {
        try {
            $json = file_get_contents(dirname(__DIR__) . '/resources/runtime-release.json');
            $release = json_decode($json === false ? '' : $json, true, 32, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            throw new AcbrException('RUNTIME_PIN_INVALIDO', 'Não foi possível ler a versão fixada do runtime.');
        }
        if (!is_array($release) || ($release['formatVersion'] ?? null) !== 1
            || ($release['sourceRevision'] ?? null) !== self::SOURCE_REVISION
            || ($release['contractHash'] ?? null) !== self::CONTRACT_HASH
            || ($release['platform'] ?? null) !== 'linux' || ($release['arch'] ?? null) !== 'x64'
            || !self::isHash($release['manifestSha256'] ?? null)
            || !self::isHash($release['archive']['sha256'] ?? null)
            || !is_int($release['archive']['bytes'] ?? null) || $release['archive']['bytes'] < 1) {
            throw new AcbrException('RUNTIME_PIN_INVALIDO', 'A versão fixada do runtime é inválida.');
        }
        return $release;
    }

    public static function resolve(?string $directory = null): self
    {
        self::requirePlatform();
        if ($directory === null) {
            $configured = getenv('ACBR_RUNTIME_DIR');
            $directory = is_string($configured) && $configured !== '' ? $configured : self::defaultDirectory();
        }
        self::assertLocalPath($directory);
        self::assertNoSymlinkAncestors($directory);
        $root = realpath($directory);
        if ($root === false || !is_dir($root)) {
            throw new AcbrException('RUNTIME_AUSENTE', 'Runtime ausente. Instale-o com runtime:install ou informe ACBR_RUNTIME_DIR.');
        }
        $manifestFile = self::regularFile($root, 'manifest.json');
        $size = filesize($manifestFile);
        if ($size === false || $size > self::MAX_MANIFEST_BYTES) {
            throw new AcbrException('RUNTIME_INVALIDO', 'Manifesto do runtime excessivo ou ilegível.');
        }
        $bytes = file_get_contents($manifestFile);
        try {
            $manifest = json_decode($bytes === false ? '' : $bytes, false, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new AcbrException('RUNTIME_INVALIDO', 'Manifesto do runtime inválido.');
        }
        if (!$manifest instanceof \stdClass || ($manifest->formatVersion ?? null) !== 1
            || ($manifest->platform ?? null) !== 'linux' || ($manifest->arch ?? null) !== 'x64'
            || !is_int($manifest->sourceRevision ?? null) || !self::isHash($manifest->contractHash ?? null)
            || !($manifest->files ?? null) instanceof \stdClass
            || !is_string($manifest->executable ?? null) || ($manifest->testOnly ?? false) !== false) {
            throw new AcbrException('RUNTIME_INVALIDO', 'Manifesto ou plataforma do runtime inválidos.');
        }
        if ($manifest->sourceRevision !== self::SOURCE_REVISION || $manifest->contractHash !== self::CONTRACT_HASH) {
            throw new AcbrException('CONTRATO_DIVERGENTE', 'Cliente e runtime usam contratos ou revisões diferentes.');
        }
        $release = self::release();
        if (!hash_equals($release['manifestSha256'], hash('sha256', $bytes))) {
            throw new AcbrException('RUNTIME_ALTERADO', 'O manifesto não corresponde à versão fixada do runtime.');
        }
        $files = get_object_vars($manifest->files);
        if ($files === [] || !isset($files[$manifest->executable])) {
            throw new AcbrException('RUNTIME_INVALIDO', 'O executável não consta no inventário do runtime.');
        }
        $directories = [];
        foreach ($files as $name => $expected) {
            if (!is_string($name) || !self::isHash($expected)) {
                throw new AcbrException('RUNTIME_INVALIDO', 'Caminho ou hash inválido no inventário.');
            }
            $file = self::regularFile($root, $name);
            $actual = hash_file('sha256', $file);
            if (!is_string($actual) || !hash_equals($expected, $actual)) {
                throw new AcbrException('RUNTIME_ALTERADO', 'Arquivo do runtime não corresponde ao SHA256 fixado: ' . $name);
            }
            for ($parent = dirname($name); $parent !== '.'; $parent = dirname($parent)) {
                $directories[$parent] = true;
            }
        }
        // Extra libraries or OpenSSL modules are also executable content; reject the entire extra inventory.
        self::checkInventory($root, '', $files + ['manifest.json' => $release['manifestSha256']], $directories);
        $executable = self::regularFile($root, $manifest->executable);
        if (!is_executable($executable)) {
            throw new AcbrException('RUNTIME_NAO_EXECUTAVEL', 'O executável verificado não possui permissão de execução.');
        }
        return new self($root, $executable, json_decode($bytes, true, 64, JSON_THROW_ON_ERROR));
    }

    /**
     * Install an already downloaded, pinned .tgz or a verified runtime directory.
     * No network, shell, native execution, replacement, or user-supplied checksum override.
     */
    public static function install(string $source, ?string $destination = null): self
    {
        self::requirePlatform();
        self::assertLocalPath($source);
        self::assertNoSymlinkAncestors($source);
        $sourcePath = realpath($source);
        if ($sourcePath === false) {
            throw new AcbrException('RUNTIME_AUSENTE', 'Arquivo ou diretório de origem não encontrado.');
        }
        $verified = is_dir($sourcePath) ? self::resolve($sourcePath) : null;
        if ($verified === null) {
            if (!is_file($sourcePath)) {
                throw new AcbrException('RUNTIME_INVALIDO', 'A origem deve ser um diretório ou arquivo regular.');
            }
            $release = self::release();
            if (filesize($sourcePath) !== $release['archive']['bytes']
                || !hash_equals($release['archive']['sha256'], (string) hash_file('sha256', $sourcePath))) {
                throw new AcbrException('RUNTIME_ALTERADO', 'O arquivo não corresponde ao tamanho e SHA256 da publicação fixada.');
            }
        }
        $destination ??= self::defaultDirectory();
        self::assertLocalPath($destination);
        self::assertNoSymlinkAncestors($destination);
        $absolute = str_starts_with($destination, '/') ? $destination : getcwd() . '/' . $destination;
        $destination = '/' . implode('/', array_filter(explode('/', $absolute), static fn (string $part): bool => $part !== '' && $part !== '.'));
        if ($verified !== null && str_starts_with($destination, $verified->directory . '/')) {
            throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'O destino não pode ficar dentro do runtime de origem.');
        }
        if (file_exists($destination) || is_link($destination)) {
            throw new AcbrException('RUNTIME_DESTINO_EXISTENTE', 'O destino já existe; escolha um novo diretório.');
        }
        $parent = dirname($destination);
        if (!is_dir($parent) && !@mkdir($parent, 0755, true) && !is_dir($parent)) {
            throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Não foi possível criar o diretório de destino.');
        }
        self::assertNoSymlinkAncestors($parent);
        $parent = realpath($parent);
        if ($parent === false) {
            throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Diretório de destino inválido.');
        }
        $destination = $parent . '/' . basename($destination);
        if ($verified !== null && str_starts_with($destination, $verified->directory . '/')) {
            throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'O destino não pode ficar dentro do runtime de origem.');
        }
        $stage = $parent . '/.acbr-runtime-' . bin2hex(random_bytes(16));
        if (!mkdir($stage, 0700)) {
            throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Não foi possível preparar a instalação.');
        }
        try {
            if ($verified !== null) {
                foreach (array_keys($verified->manifest['files']) as $relative) {
                    $from = self::regularFile($verified->directory, $relative);
                    self::makeFileParent($stage, $relative);
                    if (!copy($from, $stage . '/' . $relative) || !chmod($stage . '/' . $relative, 0644)) {
                        throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao copiar o runtime verificado.');
                    }
                }
                if (!copy(self::regularFile($verified->directory, 'manifest.json'), $stage . '/manifest.json')
                    || !chmod($stage . '/manifest.json', 0644)) {
                    throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao copiar o manifesto verificado.');
                }
            } else {
                self::extractPinnedArchive($sourcePath, $stage);
            }
            if (!is_file($stage . '/acbr-worker') || !chmod($stage . '/acbr-worker', 0755)) {
                throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao preparar o executável.');
            }
            self::resolve($stage); // Verify again after copying/extracting and before the atomic publication.
            if (file_exists($destination) || is_link($destination) || !rename($stage, $destination)) {
                throw new AcbrException('RUNTIME_DESTINO_EXISTENTE', 'O destino foi criado durante a instalação.');
            }
            if (!chmod($destination, 0755)) {
                throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao ajustar permissões do runtime instalado.');
            }
            return self::resolve($destination);
        } finally {
            if (is_dir($stage)) {
                self::removeStage($stage);
            }
        }
    }

    /** @return array<string,mixed> Read-only diagnostics; never executes the native worker or ldd. */
    public static function diagnostics(?string $directory = null): array
    {
        $result = [
            'ok' => false,
            'phpVersion' => PHP_VERSION,
            'platform' => PHP_OS_FAMILY,
            'architecture' => php_uname('m'),
            'procOpenAvailable' => function_exists('proc_open'),
            'zlibAvailable' => function_exists('gzopen'),
            'sourceRevision' => self::SOURCE_REVISION,
            'contractHash' => self::CONTRACT_HASH,
            'systemRequirement' => 'Linux x64 com glibc; runtime upstream construído para Ubuntu 24.04. Alpine/musl não é suportado.',
            'nativeExecutionChecked' => false,
        ];
        try {
            $runtime = self::resolve($directory);
            $result += ['directory' => $runtime->directory, 'executable' => $runtime->executable,
                'verifiedFiles' => count($runtime->manifest['files']), 'release' => self::release()['version']];
            $result['ok'] = $result['procOpenAvailable'];
            if (!$result['procOpenAvailable']) {
                $result['error'] = ['codigo' => 'PROC_OPEN_AUSENTE', 'mensagem' => 'Habilite proc_open no PHP para usar o motor.'];
            }
        } catch (AcbrException $error) {
            $result['error'] = ['codigo' => $error->codigo, 'mensagem' => $error->getMessage()];
        }
        return $result;
    }

    private static function requirePlatform(): void
    {
        if (PHP_OS_FAMILY !== 'Linux' || !in_array(strtolower(php_uname('m')), ['x86_64', 'amd64'], true)
            || PHP_INT_SIZE !== 8) {
            throw new AcbrException('PLATAFORMA_NAO_SUPORTADA', 'Esta versão exige PHP 64 bits em Linux x64.');
        }
    }

    private static function isHash(mixed $hash): bool
    {
        return is_string($hash) && preg_match('/\A[a-f0-9]{64}\z/D', $hash) === 1;
    }

    private static function assertLocalPath(string $path): void
    {
        if ($path === '' || str_contains($path, "\0") || str_contains($path, ':') || str_contains($path, '\\')
            || in_array('..', explode('/', $path), true)) {
            throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'Use um caminho local sem travessia de diretórios.');
        }
    }

    private static function assertNoSymlinkAncestors(string $path): void
    {
        self::assertLocalPath($path);
        if (!str_starts_with($path, '/')) {
            $cwd = getcwd();
            if ($cwd === false) {
                throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'Diretório atual indisponível.');
            }
            $path = $cwd . '/' . $path;
        }
        $current = '';
        foreach (explode('/', $path) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            $current .= '/' . $part;
            clearstatcache(true, $current);
            if (is_link($current)) {
                throw new AcbrException('RUNTIME_LINK_INVALIDO', 'Links simbólicos não são permitidos no caminho do runtime.');
            }
        }
    }

    private static function relativePath(string $relative): void
    {
        if ($relative === '' || str_starts_with($relative, '/') || str_contains($relative, '\\')
            || preg_match('/[\x00-\x1f\x7f:]/', $relative) === 1) {
            throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'Caminho inválido no inventário do runtime.');
        }
        foreach (explode('/', $relative) as $part) {
            if ($part === '' || $part === '.' || $part === '..') {
                throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'Travessia de diretórios no inventário do runtime.');
            }
        }
    }

    private static function regularFile(string $root, string $relative): string
    {
        self::relativePath($relative);
        $path = $root;
        foreach (explode('/', $relative) as $part) {
            $path .= '/' . $part;
            clearstatcache(true, $path);
            if (is_link($path)) {
                throw new AcbrException('RUNTIME_LINK_INVALIDO', 'Links simbólicos não são permitidos no runtime.');
            }
        }
        $stat = @lstat($path);
        $resolved = realpath($path);
        if ($stat === false || ($stat['mode'] & 0170000) !== 0100000 || $resolved === false
            || !str_starts_with($resolved, $root . '/') || !is_readable($path)) {
            throw new AcbrException('RUNTIME_INCOMPLETO', 'Arquivo regular do runtime ausente ou ilegível: ' . $relative);
        }
        return $path;
    }

    private static function checkInventory(string $root, string $prefix, array $files, array $directories): void
    {
        $entries = @scandir($root . ($prefix === '' ? '' : '/' . $prefix));
        if ($entries === false) {
            throw new AcbrException('RUNTIME_INCOMPLETO', 'Diretório do runtime ilegível.');
        }
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $relative = $prefix === '' ? $entry : $prefix . '/' . $entry;
            $path = $root . '/' . $relative;
            clearstatcache(true, $path);
            if (is_link($path)) {
                throw new AcbrException('RUNTIME_LINK_INVALIDO', 'Links simbólicos não são permitidos no runtime.');
            }
            if (is_dir($path) && isset($directories[$relative])) {
                self::checkInventory($root, $relative, $files, $directories);
            } elseif (!is_file($path) || !isset($files[$relative])) {
                throw new AcbrException('RUNTIME_ARQUIVO_EXTRA', 'Conteúdo não inventariado no runtime: ' . $relative);
            }
        }
    }

    private static function makeFileParent(string $root, string $relative): void
    {
        self::relativePath($relative);
        $parent = dirname($root . '/' . $relative);
        if (!is_dir($parent) && !mkdir($parent, 0755, true) && !is_dir($parent)) {
            throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao criar diretório do runtime.');
        }
        self::assertNoSymlinkAncestors($parent);
    }

    /** Restricted POSIX tar reader: no shell, links, devices, PAX/GNU metadata, traversal or overwrite. */
    private static function extractPinnedArchive(string $archive, string $destination): void
    {
        if (!function_exists('gzopen')) {
            throw new AcbrException('ZLIB_AUSENTE', 'A instalação de .tgz exige a extensão zlib do PHP.');
        }
        $stream = @gzopen($archive, 'rb');
        if ($stream === false) {
            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Arquivo gzip inválido.');
        }
        $total = 0;
        $seen = [];
        try {
            while (true) {
                $header = self::readGzip($stream, 512, $total);
                if ($header === str_repeat("\0", 512)) {
                    if (self::readGzip($stream, 512, $total) !== str_repeat("\0", 512)) {
                        throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Final do arquivo tar inválido.');
                    }
                    while (!gzeof($stream)) {
                        $tail = gzread($stream, 8192);
                        if ($tail === false || ($tail === '' && !gzeof($stream)) || trim($tail, "\0") !== '') {
                            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Conteúdo extra após o final do tar.');
                        }
                        $total += strlen($tail);
                        if ($total > self::MAX_TAR_BYTES) {
                            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Arquivo tar excessivo.');
                        }
                    }
                    return;
                }
                if (count($seen) >= 4096) {
                    throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Quantidade excessiva de entradas no tar.');
                }
                $expected = self::tarOctal(substr($header, 148, 8));
                $checksumHeader = substr_replace($header, str_repeat(' ', 8), 148, 8);
                if ($expected !== array_sum(unpack('C*', $checksumHeader))) {
                    throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Checksum de cabeçalho tar inválido.');
                }
                $type = $header[156];
                if (!in_array($type, ["\0", '0', '5'], true) || trim(substr($header, 157, 100), "\0") !== '') {
                    throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Links, arquivos especiais e metadados estendidos não são aceitos.');
                }
                $name = self::tarString(substr($header, 0, 100));
                $prefix = self::tarString(substr($header, 345, 155));
                if ($prefix !== '') {
                    $name = $prefix . '/' . $name;
                }
                if ($type === '5') {
                    $name = rtrim($name, '/');
                }
                self::relativePath($name);
                if (!str_starts_with($name, 'package/') && $name !== 'package') {
                    throw new AcbrException('RUNTIME_CAMINHO_INVALIDO', 'Arquivo fora da raiz do pacote.');
                }
                if (isset($seen[$name])) {
                    throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Entrada duplicada no arquivo tar.');
                }
                $seen[$name] = true;
                $size = self::tarOctal(substr($header, 124, 12));
                if ($size > 134217728 || ($type === '5' && $size !== 0)) {
                    throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Tamanho inválido no arquivo tar.');
                }
                $relative = str_starts_with($name, 'package/runtime/') ? substr($name, 16) : null;
                $output = null;
                if ($relative !== null && $type !== '5') {
                    self::makeFileParent($destination, $relative);
                    $output = @fopen($destination . '/' . $relative, 'xb');
                    if ($output === false) {
                        throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao extrair arquivo do runtime.');
                    }
                }
                try {
                    for ($left = $size; $left > 0;) {
                        $chunk = self::readGzip($stream, min(65536, $left), $total);
                        if (is_resource($output) && fwrite($output, $chunk) !== strlen($chunk)) {
                            throw new AcbrException('RUNTIME_INSTALACAO_FALHOU', 'Falha ao gravar arquivo do runtime.');
                        }
                        $left -= strlen($chunk);
                    }
                } finally {
                    if (is_resource($output)) {
                        fclose($output);
                        chmod($destination . '/' . $relative, 0644);
                    }
                }
                $padding = (512 - $size % 512) % 512;
                if ($padding > 0) {
                    self::readGzip($stream, $padding, $total);
                }
            }
        } finally {
            gzclose($stream);
        }
    }

    private static function readGzip(mixed $stream, int $length, int &$total): string
    {
        $bytes = '';
        while (strlen($bytes) < $length) {
            $chunk = gzread($stream, $length - strlen($bytes));
            if ($chunk === false || $chunk === '') {
                throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Arquivo tar truncado.');
            }
            $bytes .= $chunk;
        }
        $total += $length;
        if ($total > self::MAX_TAR_BYTES) {
            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Arquivo tar excessivo.');
        }
        return $bytes;
    }

    private static function tarString(string $field): string
    {
        $value = rtrim($field, "\0");
        if (str_contains($value, "\0")) {
            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Nome inválido no cabeçalho tar.');
        }
        return $value;
    }

    private static function tarOctal(string $field): int
    {
        $value = trim($field, "\0 ");
        if ($value === '' || preg_match('/\A[0-7]+\z/D', $value) !== 1 || strlen($value) > 11) {
            throw new AcbrException('RUNTIME_ARQUIVO_INVALIDO', 'Número inválido no cabeçalho tar.');
        }
        return intval($value, 8);
    }

    private static function removeStage(string $directory): void
    {
        foreach (new \FilesystemIterator($directory, \FilesystemIterator::SKIP_DOTS) as $entry) {
            if ($entry->isDir() && !$entry->isLink()) {
                self::removeStage($entry->getPathname());
            } else {
                @unlink($entry->getPathname());
            }
        }
        @rmdir($directory);
    }
}
