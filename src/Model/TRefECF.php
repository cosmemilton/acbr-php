<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TRefECF extends \Acbr\Document
{
    public const TYPE = 'TRefECF';

    public function getModelo(): ?TpcnECFModRef
    {
        $value = $this->get('modelo');
        return $value === null ? null : TpcnECFModRef::from($value);
    }

    /** @param TpcnECFModRef|string $value Validated without scalar coercion. */
    public function withModelo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['modelo' => $value]);
    }

    public function getNCOO(): ?string
    {
        $value = $this->get('nCOO');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNCOO(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nCOO' => $value]);
    }

    public function getNECF(): ?string
    {
        $value = $this->get('nECF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNECF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nECF' => $value]);
    }
}
