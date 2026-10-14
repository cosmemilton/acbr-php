<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMono extends \Acbr\Document
{
    public const TYPE = 'TgMono';

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

    public function getVCBSMonoRet(): ?string
    {
        $value = $this->get('vCBSMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSMonoRet' => $value]);
    }

    public function getVCBSMonoReten(): ?string
    {
        $value = $this->get('vCBSMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSMonoReten' => $value]);
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

    public function getVIBSMonoRet(): ?string
    {
        $value = $this->get('vIBSMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMonoRet' => $value]);
    }

    public function getVIBSMonoReten(): ?string
    {
        $value = $this->get('vIBSMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMonoReten' => $value]);
    }
}
