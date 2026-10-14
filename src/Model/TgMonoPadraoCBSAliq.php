<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoPadraoCBSAliq extends \Acbr\Document
{
    public const TYPE = 'TgMonoPadraoCBSAliq';

    public function getPAliqMonoCBS(): ?string
    {
        $value = $this->get('pAliqMonoCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqMonoCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqMonoCBS' => $value]);
    }

    public function getVBCMono(): ?string
    {
        $value = $this->get('vBCMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCMono' => $value]);
    }

    public function getVCBSMono(): ?string
    {
        $value = $this->get('vCBSMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSMono' => $value]);
    }
}
