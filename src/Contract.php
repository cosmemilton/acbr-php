<?php

declare(strict_types=1);

namespace Acbr;

use Acbr\Model\FieldHelpException;
use Acbr\Model\ModelCollection;
use Acbr\Model\ValidationException;

/** The exact upstream ACBr IR: introspection and structural input validation. */
final class Contract
{
    public const SOURCE_REVISION = 48590;
    public const CONTRACT_HASH = 'e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d';
    private static ?self $bundled = null;
    private array $metadata;
    /** @var array<string, array<string, mixed>> */
    private array $types = [];

    public function __construct(#[\SensitiveParameter] ?string $path = null)
    {
        $path ??= dirname(__DIR__) . '/resources/contract.json';
        $json = @file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException('Metadados do contrato indisponíveis.');
        }
        try {
            $metadata = json_decode($json, true, 128, JSON_THROW_ON_ERROR);
        } catch (\JsonException $error) {
            throw new \RuntimeException('Metadados do contrato inválidos.');
        }
        $this->loadMetadata($metadata);
    }

    public static function bundled(): self
    {
        return self::$bundled ??= new self();
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function types(): array
    {
        return array_values($this->types);
    }

    public function type(#[\SensitiveParameter] string $name = 'TNFe'): array
    {
        if (!isset($this->types[$name])) {
            throw new ValidationException('MODELO_DESCONHECIDO');
        }
        return $this->types[$name];
    }

    /** @return list<array<string, mixed>> Full property descriptors for an object type. */
    public function fields(#[\SensitiveParameter] string $type = 'TNFe'): array
    {
        $node = $this->type($type);
        if ($node['kind'] !== 'object') {
            throw new FieldHelpException('OBJETO_ESPERADO', 'O tipo não é um objeto com campos.');
        }
        return $node['properties'];
    }

    /**
     * Validate structure only; native schema/fiscal validation is a separate step.
     * PHP arrays may represent objects, including [] for an empty object. With
     * strictObjects, objects must be stdClass, preserving the JSON {} / [] distinction.
     */
    public function validate(#[\SensitiveParameter] mixed $value, #[\SensitiveParameter] string $type = 'TNFe', bool $strictObjects = false): void
    {
        $this->validateNode($value, $this->type($type), $type, $strictObjects, 0);
    }

    /** Return a detached JSON-compatible value with correct object/list encoding. */
    public function normalize(#[\SensitiveParameter] mixed $value, #[\SensitiveParameter] string $type = 'TNFe'): mixed
    {
        $this->validate($value, $type);
        return $this->normalizeNode($value, $this->type($type));
    }

    /** Describe a type or field, e.g. Det[].Prod.vProd, including enum values. */
    public function help(#[\SensitiveParameter] string $query = 'TNFe'): array
    {
        if (strlen($query) > 512 || !preg_match('/^[A-Za-z_]\w*(?:\[\d*\])?(?:\.[A-Za-z_]\w*(?:\[\d*\])?)*$/D', $query)) {
            throw new FieldHelpException('CAMINHO_INVALIDO', 'Use nomes separados por ponto e [] para coleções.');
        }
        $root = $this->metadata['ir']['root'];
        $base = [
            'formatVersion' => 1,
            'sourceRevision' => $this->metadata['sourceRevision'],
            'sourceHash' => $this->metadata['sourceHash'],
            'contractHash' => $this->metadata['contractHash'],
            'root' => $root,
        ];
        if (isset($this->types[$query])) {
            return $base + $this->description($this->types[$query], $query, false);
        }
        $relative = str_starts_with($query, $root . '.') ? substr($query, strlen($root) + 1) : $query;
        $node = $this->type($root);
        if (preg_match('/^[A-Za-z_]\w*$/D', $relative) && !in_array($relative, array_column($node['properties'], 'name'), true)) {
            $matches = [];
            $this->matchingPaths($root, '', $relative, [], $matches);
            sort($matches, SORT_STRING);
            if (count($matches) > 1) {
                throw new FieldHelpException('CAMPO_AMBIGUO', 'Informe o caminho completo do campo.', $matches);
            }
            if (count($matches) === 1) {
                $relative = $matches[0];
            }
        }
        $canonical = [$root];
        $readonly = false;
        $property = null;
        $selected = false;
        $parts = explode('.', $relative);
        foreach ($parts as $index => $part) {
            if ($node['kind'] !== 'object') {
                throw new FieldHelpException('CAMINHO_INVALIDO', 'Um valor simples não contém campos.');
            }
            preg_match('/^([A-Za-z_]\w*)(\[\d*\])?$/D', $part, $match);
            $property = null;
            foreach ($node['properties'] as $candidate) {
                if ($candidate['name'] === $match[1]) {
                    $property = $candidate;
                    break;
                }
            }
            if ($property === null) {
                throw new FieldHelpException('CAMPO_INEXISTENTE', 'O campo não existe nesse objeto.', array_column($node['properties'], 'name'));
            }
            $readonly = $readonly || $property['readonly'];
            $selected = isset($match[2]) && $match[2] !== '';
            $canonical[] = $property['name'] . ($selected ? '[]' : '');
            if ($selected && $property['kind'] !== 'list') {
                throw new FieldHelpException('CAMPO_NAO_COLECAO', '[] só pode ser usado em uma coleção.');
            }
            if (isset($property['ref'])) {
                $node = $this->types[$property['ref']];
                if ($property['kind'] === 'list') {
                    if ($selected) {
                        $node = $this->types[$node['item']];
                    } elseif ($index < count($parts) - 1) {
                        throw new FieldHelpException('COLECAO_REQUER_INDICE', 'Use [] para consultar o elemento da coleção.');
                    }
                }
            } else {
                $node = ['name' => $property['type'], 'kind' => $property['kind']];
                if ($index < count($parts) - 1) {
                    throw new FieldHelpException('CAMINHO_INVALIDO', 'Um valor simples não contém campos.');
                }
            }
        }
        $result = $base + $this->description($node, implode('.', $canonical), $readonly);
        $result['pascalType'] = $selected ? $node['name'] : $property['type'];
        if (isset($property['ref'])) {
            $result['typeReference'] = $selected ? $node['name'] : $property['ref'];
        }
        if (isset($property['declaredDefault'])) {
            $result['declaredDefault'] = $property['declaredDefault'];
        }
        $constraints = array_intersect_key($property, array_flip(['min', 'max', 'scale']));
        if ($constraints !== []) {
            $result['constraints'] = $constraints;
        }
        return $result;
    }

    public function formatHelp(#[\SensitiveParameter] string $query = 'TNFe'): string
    {
        $help = $this->help($query);
        $lines = [$help['path'], 'Tipo: ' . $help['pascalType'] . ' (' . $help['kind'] . '; entrada ' . $help['inputType'] . ')', 'Somente leitura: ' . ($help['readonly'] ? 'sim' : 'não')];
        if (isset($help['values'])) {
            $lines[] = 'Valores permitidos: ' . implode(', ', $help['values']);
        }
        if (isset($help['declaredDefault'])) {
            $lines[] = 'Padrão declarado no Pascal: ' . $help['declaredDefault'] . ' (a omissão preserva o construtor nativo).';
        }
        if (isset($help['constraints'])) {
            $lines[] = 'Limites: ' . json_encode($help['constraints'], JSON_THROW_ON_ERROR);
        }
        if (isset($help['itemType'])) {
            $lines[] = 'Tipo do elemento: ' . $help['itemType'];
        }
        if (isset($help['members'])) {
            $lines[] = 'Campos: ' . implode(', ', array_column($help['members'], 'name'));
        }
        $lines[] = 'Revisão ACBr: ' . $help['sourceRevision'];
        $lines[] = 'Contrato: ' . $help['contractHash'];
        return implode("\n", $lines);
    }

    private function loadMetadata(#[\SensitiveParameter] mixed $metadata): void
    {
        $invalid = static function (): never {
            throw new \RuntimeException('Metadados do contrato inválidos ou incompatíveis.');
        };
        if (!is_array($metadata) || ($metadata['formatVersion'] ?? null) !== 1
            || ($metadata['sourceRevision'] ?? null) !== self::SOURCE_REVISION
            || ($metadata['contractHash'] ?? null) !== self::CONTRACT_HASH
            || !is_string($metadata['sourceHash'] ?? null)
            || !preg_match('/^[a-f0-9]{64}$/D', $metadata['sourceHash'])
            || ($metadata['ir']['schemaVersion'] ?? null) !== 1
            || !is_array($metadata['ir']['types'] ?? null)) {
            $invalid();
        }
        $digest = hash('sha256', json_encode([
            'sourceRevision' => $metadata['sourceRevision'],
            'sourceHash' => $metadata['sourceHash'],
            'ir' => $metadata['ir'],
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        if (!hash_equals(self::CONTRACT_HASH, $digest)) {
            $invalid();
        }
        foreach ($metadata['ir']['types'] as $node) {
            if (!is_array($node) || !preg_match('/^[A-Za-z_]\w*$/D', $node['name'] ?? '')
                || isset($this->types[$node['name']]) || !in_array($node['kind'] ?? null, ['object', 'enum', 'list'], true)) {
                $invalid();
            }
            $this->types[$node['name']] = $node;
        }
        if (($this->types[$metadata['ir']['root'] ?? '']['kind'] ?? null) !== 'object') {
            $invalid();
        }
        // The pinned digest authenticates every field, reference, constraint and enum.
        $this->metadata = $metadata;
    }

    private function validateNode(#[\SensitiveParameter] mixed $value, array $node, string $at, bool $strict, int $depth): void
    {
        if ($depth > 128) {
            throw new ValidationException('PROFUNDIDADE_EXCEDIDA', $at);
        }
        if ($value instanceof Document || $value instanceof ModelCollection) {
            if ($value->typeName() !== $node['name']) {
                throw new ValidationException($node['kind'] === 'list' ? 'ARRAY_ESPERADO' : 'OBJETO_ESPERADO', $at);
            }
            $value = $value->jsonSerialize();
        }
        if ($node['kind'] === 'enum') {
            $value = $value instanceof \BackedEnum ? $value->value : $value;
            if (!is_string($value) || !in_array($value, $node['values'], true)) {
                throw new ValidationException('ENUM_INVALIDO', $at);
            }
            return;
        }
        if ($node['kind'] === 'list') {
            if (!is_array($value) || !array_is_list($value)) {
                throw new ValidationException('ARRAY_ESPERADO', $at);
            }
            foreach ($value as $index => $item) {
                $this->validateNode($item, $this->types[$node['item']], $at . '[' . $index . ']', $strict, $depth + 1);
            }
            return;
        }
        if (!$value instanceof \stdClass && ($strict || !is_array($value) || ($value !== [] && array_is_list($value)))) {
            throw new ValidationException('OBJETO_ESPERADO', $at);
        }
        $properties = array_column($node['properties'], null, 'name');
        foreach ($value as $name => $fieldValue) {
            if (!isset($properties[$name])) {
                // Deliberately exclude unknown keys and all submitted values from errors.
                throw new ValidationException('CAMPO_DESCONHECIDO', $at);
            }
            $property = $properties[$name];
            $field = $at . '.' . $name;
            if ($property['readonly']) {
                throw new ValidationException('CAMPO_SOMENTE_LEITURA', $field);
            }
            if (isset($property['ref'])) {
                $this->validateNode($fieldValue, $this->types[$property['ref']], $field, $strict, $depth + 1);
                continue;
            }
            switch ($property['kind']) {
                case 'integer':
                    if (!is_int($fieldValue) || $fieldValue < $property['min'] || $fieldValue > $property['max']) {
                        throw new ValidationException('INTEIRO_INVALIDO', $field);
                    }
                    break;
                case 'boolean':
                    if (!is_bool($fieldValue)) {
                        throw new ValidationException('BOOLEANO_ESPERADO', $field);
                    }
                    break;
                case 'decimal':
                    if (!is_string($fieldValue) || strlen($fieldValue) > 64
                        || !preg_match('/^-?\d+(?:\.\d+)?$/D', $fieldValue)
                        || (isset($property['scale']) && strlen(explode('.', $fieldValue, 2)[1] ?? '') > $property['scale'])) {
                        throw new ValidationException('DECIMAL_INVALIDO', $field);
                    }
                    break;
                case 'datetime':
                    $this->validateDate($fieldValue, $field);
                    break;
                default:
                    if (!is_string($fieldValue)) {
                        throw new ValidationException('TEXTO_ESPERADO', $field);
                    }
            }
        }
    }

    private function validateDate(#[\SensitiveParameter] mixed $value, string $at): void
    {
        if (!is_string($value) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:T(\d{2}):(\d{2}):(\d{2})(?:\.\d{1,3})?)?$/D', $value, $parts)) {
            throw new ValidationException('DATA_HORA_INVALIDA', $at);
        }
        if (!checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])
            || (isset($parts[4]) && ((int) $parts[4] > 23 || (int) $parts[5] > 59 || (int) $parts[6] > 59))) {
            throw new ValidationException('DATA_INVALIDA', $at);
        }
        // No timezone conversion, DateTime construction, or civil-time normalization.
    }

    private function normalizeNode(#[\SensitiveParameter] mixed $value, array $node): mixed
    {
        if ($value instanceof Document || $value instanceof ModelCollection) {
            $value = $value->jsonSerialize();
        }
        if ($node['kind'] === 'enum') {
            return $value instanceof \BackedEnum ? $value->value : $value;
        }
        if ($node['kind'] === 'list') {
            return array_map(fn (#[\SensitiveParameter] mixed $item): mixed => $this->normalizeNode($item, $this->types[$node['item']]), $value);
        }
        $result = new \stdClass();
        $properties = array_column($node['properties'], null, 'name');
        foreach ($value as $name => $field) {
            $property = $properties[$name];
            $result->{$name} = isset($property['ref']) ? $this->normalizeNode($field, $this->types[$property['ref']]) : $field;
        }
        return $result;
    }

    private function description(array $node, string $path, bool $readonly): array
    {
        $result = [
            'path' => $path,
            'kind' => $node['kind'],
            'pascalType' => $node['name'],
            'inputType' => match ($node['kind']) {
                'integer' => 'integer', 'boolean' => 'boolean', 'object' => 'object', 'list' => 'array', default => 'string',
            },
            'readonly' => $readonly,
        ];
        if ($node['kind'] === 'enum') {
            $result['values'] = $node['values'];
        } elseif ($node['kind'] === 'list') {
            $result['itemType'] = $node['item'];
        } elseif ($node['kind'] === 'object') {
            $result['members'] = array_map(static fn (array $p): array => [
                'name' => $p['name'], 'kind' => $p['kind'], 'pascalType' => $p['type'], 'readonly' => $readonly || $p['readonly'],
            ], $node['properties']);
        }
        return $result;
    }

    private function matchingPaths(string $type, string $prefix, #[\SensitiveParameter] string $field, array $trail, array &$matches): void
    {
        if (isset($trail[$type]) || count($trail) > 128 || count($matches) > 1000) {
            throw new FieldHelpException('CAMINHO_COMPLETO_NECESSARIO', 'Informe o caminho completo do campo.');
        }
        $trail[$type] = true;
        $node = $this->types[$type];
        if ($node['kind'] === 'list') {
            $this->matchingPaths($node['item'], $prefix . '[]', $field, $trail, $matches);
        } elseif ($node['kind'] === 'object') {
            foreach ($node['properties'] as $property) {
                $path = ($prefix === '' ? '' : $prefix . '.') . $property['name'];
                if ($property['name'] === $field) {
                    $matches[] = $path;
                }
                if (isset($property['ref']) && $property['kind'] !== 'enum') {
                    $this->matchingPaths($property['ref'], $path, $field, $trail, $matches);
                }
            }
        }
    }
}
