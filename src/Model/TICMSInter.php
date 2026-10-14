<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMSInter extends \Acbr\Document
{
    public const TYPE = 'TICMSInter';

    public function getVBCICMSSTDest(): ?string
    {
        $value = $this->get('vBCICMSSTDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCICMSSTDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCICMSSTDest' => $value]);
    }

    public function getVICMSSTDest(): ?string
    {
        $value = $this->get('vICMSSTDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSTDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSTDest' => $value]);
    }
}
