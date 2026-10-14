<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TinfNFeSupl extends \Acbr\Document
{
    public const TYPE = 'TinfNFeSupl';

    public function getQrCode(): ?string
    {
        $value = $this->get('qrCode');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQrCode(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qrCode' => $value]);
    }

    public function getUrlChave(): ?string
    {
        $value = $this->get('urlChave');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUrlChave(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['urlChave' => $value]);
    }
}
