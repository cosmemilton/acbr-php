<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgTransfCred extends \Acbr\Document
{
    public const TYPE = 'TgTransfCred';

    public function getVCBS(): ?string
    {
        $value = $this->get('vCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBS' => $value]);
    }

    public function getVIBS(): ?string
    {
        $value = $this->get('vIBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBS' => $value]);
    }
}
