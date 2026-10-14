<?php

declare(strict_types=1);

namespace Acbr\Model;

use Acbr\Contract;
use Acbr\Document;

/** @implements \IteratorAggregate<int, Document> */
abstract class ModelCollection implements \JsonSerializable, \Countable, \IteratorAggregate
{
    public const TYPE = '';
    private readonly string $json;
    protected readonly Contract $contract;

    public function __construct(#[\SensitiveParameter] array $items = [], ?Contract $contract = null)
    {
        $this->contract = $contract ?? Contract::bundled();
        try {
            $this->json = json_encode($this->contract->normalize($items, static::TYPE), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\JsonException) {
            // Native JSON exception traces contain the submitted argument.
            throw new ValidationException('JSON_INVALIDO', static::TYPE);
        }
    }

    public static function fromArray(#[\SensitiveParameter] array $items, ?Contract $contract = null): static
    {
        return new static($items, $contract);
    }

    public static function fromJson(#[\SensitiveParameter] string $json, ?Contract $contract = null): static
    {
        $contract ??= Contract::bundled();
        try {
            $items = json_decode($json, false, 128, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            // Never chain the original native exception: its trace exposes JSON.
            throw new ValidationException('JSON_INVALIDO', static::TYPE);
        }
        $contract->validate($items, static::TYPE, true);
        return new static($items, $contract);
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

    public function toArray(): array
    {
        return json_decode($this->json, true, 128, JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): array
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

    public function count(): int
    {
        return count($this->toArray());
    }

    public function get(int $index): Document
    {
        $items = $this->toArray();
        if (!array_key_exists($index, $items)) {
            throw new \OutOfBoundsException('Índice fora da coleção.');
        }
        $class = __NAMESPACE__ . '\\' . $this->contract->type(static::TYPE)['item'];
        return new $class($items[$index], $this->contract);
    }

    public function withItem(#[\SensitiveParameter] mixed $item): static
    {
        return new static([...$this->toArray(), $item], $this->contract);
    }

    public function without(int $index): static
    {
        $items = $this->toArray();
        if (!array_key_exists($index, $items)) {
            throw new \OutOfBoundsException('Índice fora da coleção.');
        }
        array_splice($items, $index, 1);
        return new static($items, $this->contract);
    }

    public function getIterator(): \Traversable
    {
        foreach (array_keys($this->toArray()) as $index) {
            yield $index => $this->get($index);
        }
    }
}
