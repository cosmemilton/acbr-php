<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSUFTot extends \Acbr\Document
{
    public const TYPE = 'TgIBSUFTot';

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

    public function getVIBSUF(): ?string
    {
        $value = $this->get('vIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSUF' => $value]);
    }
}
