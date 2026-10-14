<?php

declare(strict_types=1);

/** Deterministic PHP projection of the bundled, upstream-generated ACBr IR. */
$root = dirname(__DIR__);
require_once $root . '/src/Contract.php';
require_once $root . '/src/Model/ValidationException.php';
$contract = new \Acbr\Contract();
$directory = $root . '/src/Model';
$check = in_array('--check', $argv, true);
$expected = [];
$header = "<?php\n\ndeclare(strict_types=1);\n\nnamespace Acbr\\Model;\n\n// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.\n";
foreach ($contract->types() as $type) {
    $name = $type['name'];
    if ($type['kind'] === 'enum') {
        $code = $header . "enum {$name}: string\n{\n";
        foreach ($type['values'] as $value) {
            $code .= "    case {$value} = '{$value}';\n";
        }
        $code .= "}\n";
    } elseif ($type['kind'] === 'list') {
        $item = $type['item'];
        $code = $header . "/** @extends ModelCollection */\nfinal class {$name} extends ModelCollection\n{\n    public const TYPE = '{$name}';\n\n    public function get(int \$index): {$item}\n    {\n        return parent::get(\$index);\n    }\n}\n";
    } else {
        $code = $header . "final class {$name} extends \\Acbr\\Document\n{\n    public const TYPE = '{$name}';\n";
        foreach ($type['properties'] as $property) {
            $field = $property['name'];
            $method = ucfirst($field);
            $kind = $property['kind'];
            $return = $property['ref'] ?? match ($kind) {
                'integer' => 'int', 'boolean' => 'bool', default => 'string',
            };
            $convert = match ($kind) {
                'object', 'list' => 'new ' . $return . '(\$value, \$this->contract)',
                'enum' => $return . '::from(\$value)',
                default => '\$value',
            };
            $convert = str_replace('\\$', '$', $convert);
            $code .= "\n    public function get{$method}(): ?{$return}\n    {\n        \$value = \$this->get('{$field}');\n        return \$value === null ? null : {$convert};\n    }\n";
            if ($property['readonly']) {
                continue;
            }
            $param = match ($kind) {
                'object' => $return . '|array|\\stdClass',
                'list' => $return . '|array',
                'enum' => $return . '|string',
                default => $return,
            };
            // mixed prevents PHP's caller-side weak scalar coercion (especially decimals).
            $code .= "\n    /** @param {$param} \$value Validated without scalar coercion. */\n    public function with{$method}(#[\\SensitiveParameter] mixed \$value): static\n    {\n        return \$this->with(['{$field}' => \$value]);\n    }\n";
        }
        $code .= "}\n";
    }
    $expected[$directory . '/' . $name . '.php'] = $code;
}
$failures = [];
foreach ($expected as $path => $content) {
    if ($check) {
        if (!is_file($path) || file_get_contents($path) !== $content) {
            $failures[] = basename($path);
        }
    } elseif (file_put_contents($path, $content) !== strlen($content)) {
        throw new \RuntimeException('Falha ao escrever modelo gerado.');
    }
}
foreach (glob($directory . '/*.php') ?: [] as $path) {
    if (isset($expected[$path])) {
        continue;
    }
    // Never touch handwritten support classes; stale generated models are an error.
    if (str_contains((string) file_get_contents($path), '// Generated from the pinned ACBr contract.')) {
        $failures[] = basename($path) . ' (obsolete)';
    }
}
if ($failures !== []) {
    fwrite(STDERR, 'Generated models differ: ' . implode(', ', $failures) . "\n");
    exit(1);
}
fwrite(STDOUT, ($check ? 'Verified ' : 'Generated ') . count($expected) . " ACBr models.\n");
