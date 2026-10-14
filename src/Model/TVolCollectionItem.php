<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TVolCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TVolCollectionItem';

    public function getEsp(): ?string
    {
        $value = $this->get('esp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withEsp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['esp' => $value]);
    }

    public function getLacres(): ?TLacresCollection
    {
        $value = $this->get('Lacres');
        return $value === null ? null : new TLacresCollection($value, $this->contract);
    }

    /** @param TLacresCollection|array $value Validated without scalar coercion. */
    public function withLacres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Lacres' => $value]);
    }

    public function getMarca(): ?string
    {
        $value = $this->get('marca');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withMarca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['marca' => $value]);
    }

    public function getNVol(): ?string
    {
        $value = $this->get('nVol');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNVol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nVol' => $value]);
    }

    public function getPesoB(): ?string
    {
        $value = $this->get('pesoB');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPesoB(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pesoB' => $value]);
    }

    public function getPesoL(): ?string
    {
        $value = $this->get('pesoL');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPesoL(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pesoL' => $value]);
    }

    public function getQVol(): ?int
    {
        $value = $this->get('qVol');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withQVol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qVol' => $value]);
    }
}
