<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoPadraoIBSAliq extends \Acbr\Document
{
    public const TYPE = 'TgMonoPadraoIBSAliq';

    public function getPAliqMonoMun(): ?string
    {
        $value = $this->get('pAliqMonoMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqMonoMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqMonoMun' => $value]);
    }

    public function getPAliqMonoUF(): ?string
    {
        $value = $this->get('pAliqMonoUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqMonoUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqMonoUF' => $value]);
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

    public function getVIBSMono(): ?string
    {
        $value = $this->get('vIBSMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMono' => $value]);
    }

    public function getVIBSMonoMun(): ?string
    {
        $value = $this->get('vIBSMonoMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMonoMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMonoMun' => $value]);
    }

    public function getVIBSMonoUF(): ?string
    {
        $value = $this->get('vIBSMonoUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMonoUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMonoUF' => $value]);
    }
}
