<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TAdiCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TAdiCollectionItem';

    public function getCFabricante(): ?string
    {
        $value = $this->get('cFabricante');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCFabricante(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cFabricante' => $value]);
    }

    public function getNAdicao(): ?int
    {
        $value = $this->get('nAdicao');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNAdicao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nAdicao' => $value]);
    }

    public function getNDraw(): ?string
    {
        $value = $this->get('nDraw');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNDraw(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nDraw' => $value]);
    }

    public function getNSeqAdi(): ?int
    {
        $value = $this->get('nSeqAdi');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNSeqAdi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nSeqAdi' => $value]);
    }

    public function getVDescDI(): ?string
    {
        $value = $this->get('vDescDI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDescDI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDescDI' => $value]);
    }
}
