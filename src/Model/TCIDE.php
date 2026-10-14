<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCIDE extends \Acbr\Document
{
    public const TYPE = 'TCIDE';

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

    public function getVCIDE(): ?string
    {
        $value = $this->get('vCIDE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCIDE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCIDE' => $value]);
    }
}
