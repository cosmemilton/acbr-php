<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TPISST extends \Acbr\Document
{
    public const TYPE = 'TPISST';

    public function getIndSomaPISST(): ?TIndSomaPISST
    {
        $value = $this->get('indSomaPISST');
        return $value === null ? null : TIndSomaPISST::from($value);
    }

    /** @param TIndSomaPISST|string $value Validated without scalar coercion. */
    public function withIndSomaPISST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indSomaPISST' => $value]);
    }

    public function getPPis(): ?string
    {
        $value = $this->get('pPis');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPPis(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pPis' => $value]);
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

    public function getVBc(): ?string
    {
        $value = $this->get('vBc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBc' => $value]);
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
