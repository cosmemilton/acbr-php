<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgpBioDiferencaCBS extends \Acbr\Document
{
    public const TYPE = 'TgpBioDiferencaCBS';

    public function getQBCBioComb(): ?string
    {
        $value = $this->get('qBCBioComb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCBioComb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCBioComb' => $value]);
    }

    public function getVCBSDiferenca(): ?string
    {
        $value = $this->get('vCBSDiferenca');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSDiferenca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSDiferenca' => $value]);
    }
}
