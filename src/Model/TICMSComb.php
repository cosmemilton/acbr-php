<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMSComb extends \Acbr\Document
{
    public const TYPE = 'TICMSComb';

    public function getVBCICMS(): ?string
    {
        $value = $this->get('vBCICMS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCICMS' => $value]);
    }

    public function getVBCICMSST(): ?string
    {
        $value = $this->get('vBCICMSST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCICMSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCICMSST' => $value]);
    }

    public function getVICMS(): ?string
    {
        $value = $this->get('vICMS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMS' => $value]);
    }

    public function getVICMSST(): ?string
    {
        $value = $this->get('vICMSST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSST' => $value]);
    }
}
