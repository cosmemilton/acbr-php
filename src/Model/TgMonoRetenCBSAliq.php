<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetenCBSAliq extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetenCBSAliq';

    public function getPAliqMonoReten(): ?string
    {
        $value = $this->get('pAliqMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqMonoReten' => $value]);
    }

    public function getVBCMonoReten(): ?string
    {
        $value = $this->get('vBCMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCMonoReten' => $value]);
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
}
