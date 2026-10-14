<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TguiaTransito extends \Acbr\Document
{
    public const TYPE = 'TguiaTransito';

    public function getNGuia(): ?string
    {
        $value = $this->get('nGuia');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNGuia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nGuia' => $value]);
    }

    public function getSerieGuia(): ?string
    {
        $value = $this->get('serieGuia');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withSerieGuia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['serieGuia' => $value]);
    }

    public function getTpGuia(): ?TtpGuia
    {
        $value = $this->get('tpGuia');
        return $value === null ? null : TtpGuia::from($value);
    }

    /** @param TtpGuia|string $value Validated without scalar coercion. */
    public function withTpGuia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpGuia' => $value]);
    }

    public function getUFGuia(): ?string
    {
        $value = $this->get('UFGuia');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFGuia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFGuia' => $value]);
    }
}
