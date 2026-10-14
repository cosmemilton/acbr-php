<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgpBioDiferencaIBS extends \Acbr\Document
{
    public const TYPE = 'TgpBioDiferencaIBS';

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

    public function getVIBSDiferenca(): ?string
    {
        $value = $this->get('vIBSDiferenca');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSDiferenca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSDiferenca' => $value]);
    }
}
