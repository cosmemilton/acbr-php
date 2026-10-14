<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgDevTrib extends \Acbr\Document
{
    public const TYPE = 'TgDevTrib';

    public function getPDevTrib(): ?string
    {
        $value = $this->get('pDevTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPDevTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pDevTrib' => $value]);
    }

    public function getVDevTrib(): ?string
    {
        $value = $this->get('vDevTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDevTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDevTrib' => $value]);
    }
}
