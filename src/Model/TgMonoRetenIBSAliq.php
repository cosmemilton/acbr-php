<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetenIBSAliq extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetenIBSAliq';

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
