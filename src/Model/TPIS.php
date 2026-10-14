<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TPIS extends \Acbr\Document
{
    public const TYPE = 'TPIS';

    public function getCST(): ?TCSTPis
    {
        $value = $this->get('CST');
        return $value === null ? null : TCSTPis::from($value);
    }

    /** @param TCSTPis|string $value Validated without scalar coercion. */
    public function withCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CST' => $value]);
    }

    public function getPPIS(): ?string
    {
        $value = $this->get('pPIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pPIS' => $value]);
    }

    public function getQBCProd(): ?string
    {
        $value = $this->get('qBCProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCProd' => $value]);
    }

    public function getVAliqProd(): ?string
    {
        $value = $this->get('vAliqProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVAliqProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vAliqProd' => $value]);
    }

    public function getVBC(): ?string
    {
        $value = $this->get('vBC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBC' => $value]);
    }

    public function getVPIS(): ?string
    {
        $value = $this->get('vPIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vPIS' => $value]);
    }
}
