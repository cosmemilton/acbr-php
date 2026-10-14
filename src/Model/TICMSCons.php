<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMSCons extends \Acbr\Document
{
    public const TYPE = 'TICMSCons';

    public function getUFcons(): ?string
    {
        $value = $this->get('UFcons');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFcons(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFcons' => $value]);
    }

    public function getVBCICMSSTCons(): ?string
    {
        $value = $this->get('vBCICMSSTCons');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCICMSSTCons(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCICMSSTCons' => $value]);
    }

    public function getVICMSSTCons(): ?string
    {
        $value = $this->get('vICMSSTCons');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSTCons(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSTCons' => $value]);
    }
}
