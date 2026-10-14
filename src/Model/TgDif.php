<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgDif extends \Acbr\Document
{
    public const TYPE = 'TgDif';

    public function getPDif(): ?string
    {
        $value = $this->get('pDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pDif' => $value]);
    }

    public function getVDif(): ?string
    {
        $value = $this->get('vDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDif' => $value]);
    }
}
