<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSMonoAdValorem extends \Acbr\Document
{
    public const TYPE = 'TgIBSMonoAdValorem';

    public function getGMonoPadrao(): ?TgMonoPadraoIBSAliq
    {
        $value = $this->get('gMonoPadrao');
        return $value === null ? null : new TgMonoPadraoIBSAliq($value, $this->contract);
    }

    /** @param TgMonoPadraoIBSAliq|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoPadrao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoPadrao' => $value]);
    }

    public function getGMonoRet(): ?TgMonoRetIBS
    {
        $value = $this->get('gMonoRet');
        return $value === null ? null : new TgMonoRetIBS($value, $this->contract);
    }

    /** @param TgMonoRetIBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoRet' => $value]);
    }

    public function getGMonoReten(): ?TgMonoRetenIBSAliq
    {
        $value = $this->get('gMonoReten');
        return $value === null ? null : new TgMonoRetenIBSAliq($value, $this->contract);
    }

    /** @param TgMonoRetenIBSAliq|array|\stdClass $value Validated without scalar coercion. */
    public function withGMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMonoReten' => $value]);
    }

    public function getGpBioDiferenca(): ?TgpBioDiferencaIBS
    {
        $value = $this->get('gpBioDiferenca');
        return $value === null ? null : new TgpBioDiferencaIBS($value, $this->contract);
    }

    /** @param TgpBioDiferencaIBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGpBioDiferenca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gpBioDiferenca' => $value]);
    }
}
