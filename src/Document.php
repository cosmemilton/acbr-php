<?php

declare(strict_types=1);

namespace Acbr;

use Acbr\Model\ValidationException;

/**
 * Immutable, validated TNFe input. Generated object models specialize TYPE.
 * Omitted values stay omitted; this class never fills native or fiscal defaults.
 */
class Document implements \JsonSerializable
{
    public const TYPE = 'TNFe';
    private readonly string $json;
    protected readonly Contract $contract;

    public function __construct(#[\SensitiveParameter] array|\stdClass $input = [], ?Contract $contract = null)
    {
        $this->contract = $contract ?? Contract::bundled();
        if ($this->contract->type(static::TYPE)['kind'] !== 'object') {
            throw new ValidationException('OBJETO_ESPERADO');
        }
        try {
            $this->json = json_encode($this->contract->normalize($input, static::TYPE), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\JsonException) {
            // Native JSON exception traces contain the submitted argument.
            throw new ValidationException('JSON_INVALIDO', static::TYPE);
        }
    }

    public static function fromArray(#[\SensitiveParameter] array $input, ?Contract $contract = null): static
    {
        return new static($input, $contract);
    }

    public static function fromJson(#[\SensitiveParameter] string $json, ?Contract $contract = null): static
    {
        $contract ??= Contract::bundled();
        try {
            $input = json_decode($json, false, 128, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            // Never chain the original native exception: its trace exposes JSON.
            throw new ValidationException('JSON_INVALIDO', static::TYPE);
        }
        $contract->validate($input, static::TYPE, true);
        return new static($input, $contract);
    }

    final public function __set(#[\SensitiveParameter] string $name, #[\SensitiveParameter] mixed $value): void
    {
        throw new \LogicException('Modelos são imutáveis; use os métodos with.');
    }

    final public function __unset(#[\SensitiveParameter] string $name): void
    {
        throw new \LogicException('Modelos são imutáveis; use without.');
    }

    public function typeName(): string
    {
        return static::TYPE;
    }

    /** A detached PHP array; use toJson()/jsonSerialize() to preserve {} versus []. */
    public function toArray(): array
    {
        return json_decode($this->json, true, 128, JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): \stdClass
    {
        return json_decode($this->json, false, 128, JSON_THROW_ON_ERROR);
    }

    public function toJson(int $flags = 0): string
    {
        if (($flags & (JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT)) !== 0) {
            throw new \InvalidArgumentException('Opções JSON não podem alterar decimais ou coleções.');
        }
        return $flags === 0 ? $this->json : json_encode($this->jsonSerialize(), $flags | JSON_THROW_ON_ERROR);
    }

    /** Replace top-level fields; nested objects are replaced, never silently merged. */
    public function with(#[\SensitiveParameter] array $changes): static
    {
        return new static(array_replace($this->toArray(), $changes), $this->contract);
    }

    public function without(#[\SensitiveParameter] string ...$fields): static
    {
        $values = $this->toArray();
        foreach ($fields as $field) {
            $this->assertField($field);
            unset($values[$field]);
        }
        return new static($values, $this->contract);
    }

    /** Return a detached PHP value; generated getters also hydrate nested models. */
    public function get(#[\SensitiveParameter] string $field): mixed
    {
        $this->assertField($field);
        return $this->toArray()[$field] ?? null;
    }

    public function has(#[\SensitiveParameter] string $field): bool
    {
        $this->assertField($field);
        return array_key_exists($field, $this->toArray());
    }

    private function assertField(#[\SensitiveParameter] string $field): void
    {
        foreach ($this->contract->fields(static::TYPE) as $property) {
            if ($property['name'] === $field) {
                return;
            }
        }
        throw new ValidationException('CAMPO_DESCONHECIDO', static::TYPE);
    }
}
