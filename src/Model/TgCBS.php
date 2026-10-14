<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgCBS extends \Acbr\Document
{
    public const TYPE = 'TgCBS';

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
}
