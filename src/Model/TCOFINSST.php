<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCOFINSST extends \Acbr\Document
{
    public const TYPE = 'TCOFINSST';

    public function getIndSomaCOFINSST(): ?TIndSomaCOFINSST
    {
        $value = $this->get('indSomaCOFINSST');
        return $value === null ? null : TIndSomaCOFINSST::from($value);
    }

    /** @param TIndSomaCOFINSST|string $value Validated without scalar coercion. */
    public function withIndSomaCOFINSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indSomaCOFINSST' => $value]);
    }

    public function getPCOFINS(): ?string
    {
        $value = $this->get('pCOFINS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pCOFINS' => $value]);
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

    public function getVCOFINS(): ?string
    {
        $value = $this->get('vCOFINS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCOFINS' => $value]);
    }
}
