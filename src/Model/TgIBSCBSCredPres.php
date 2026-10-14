<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSCBSCredPres extends \Acbr\Document
{
    public const TYPE = 'TgIBSCBSCredPres';

    public function getPCredPres(): ?string
    {
        $value = $this->get('pCredPres');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pCredPres' => $value]);
    }

    public function getVCredPres(): ?string
    {
        $value = $this->get('vCredPres');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPres' => $value]);
    }

    public function getVCredPresCondSus(): ?string
    {
        $value = $this->get('vCredPresCondSus');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPresCondSus(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPresCondSus' => $value]);
    }
}
