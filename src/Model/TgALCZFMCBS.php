<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgALCZFMCBS extends \Acbr\Document
{
    public const TYPE = 'TgALCZFMCBS';

    public function getNProcSuframa(): ?string
    {
        $value = $this->get('nProcSuframa');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNProcSuframa(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nProcSuframa' => $value]);
    }

    public function getPAliqEfetRegCBS(): ?string
    {
        $value = $this->get('pAliqEfetRegCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqEfetRegCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqEfetRegCBS' => $value]);
    }

    public function getTpALCZFMCBS(): ?TtpALCZFMCBS
    {
        $value = $this->get('tpALCZFMCBS');
        return $value === null ? null : TtpALCZFMCBS::from($value);
    }

    /** @param TtpALCZFMCBS|string $value Validated without scalar coercion. */
    public function withTpALCZFMCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpALCZFMCBS' => $value]);
    }

    public function getVTribRegCBS(): ?string
    {
        $value = $this->get('vTribRegCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribRegCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribRegCBS' => $value]);
    }
}
