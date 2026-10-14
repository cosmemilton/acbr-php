<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgRed extends \Acbr\Document
{
    public const TYPE = 'TgRed';

    public function getPAliqEfet(): ?string
    {
        $value = $this->get('pAliqEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqEfet' => $value]);
    }

    public function getPRedAliq(): ?string
    {
        $value = $this->get('pRedAliq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedAliq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedAliq' => $value]);
    }
}
