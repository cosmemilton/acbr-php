<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TFat extends \Acbr\Document
{
    public const TYPE = 'TFat';

    public function getNFat(): ?string
    {
        $value = $this->get('nFat');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNFat(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nFat' => $value]);
    }

    public function getVDesc(): ?string
    {
        $value = $this->get('vDesc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDesc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDesc' => $value]);
    }

    public function getVLiq(): ?string
    {
        $value = $this->get('vLiq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVLiq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vLiq' => $value]);
    }

    public function getVOrig(): ?string
    {
        $value = $this->get('vOrig');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVOrig(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vOrig' => $value]);
    }
}
