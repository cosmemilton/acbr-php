<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgCBSMonoAdValorem extends \Acbr\Document
{
    public const TYPE = 'TgCBSMonoAdValorem';

    public function getGMonoPadrao(): ?TgMonoPadraoCBSAliq
    {
        $value = $this->get('gMonoPadrao');
        return $value === null ? null : new TgMonoPadraoCBSAliq($value, $this->contract);
    }

    /** @param TgMonoPadraoCBSAliq|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoPadrao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoPadrao' => $value]);
    }

    public function getGMonoRet(): ?TgMonoRetCBS
    {
        $value = $this->get('gMonoRet');
        return $value === null ? null : new TgMonoRetCBS($value, $this->contract);
    }

    /** @param TgMonoRetCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoRet' => $value]);
    }

    public function getGMonoReten(): ?TgMonoRetenCBSAliq
    {
        $value = $this->get('gMonoReten');
        return $value === null ? null : new TgMonoRetenCBSAliq($value, $this->contract);
    }

    /** @param TgMonoRetenCBSAliq|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoReten' => $value]);
    }

    public function getGpBioDiferenca(): ?TgpBioDiferencaCBS
    {
        $value = $this->get('gpBioDiferenca');
        return $value === null ? null : new TgpBioDiferencaCBS($value, $this->contract);
    }

    /** @param TgpBioDiferencaCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGpBioDiferenca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gpBioDiferenca' => $value]);
    }
}
