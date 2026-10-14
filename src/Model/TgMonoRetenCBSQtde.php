<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetenCBSQtde extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetenCBSQtde';

    public function getAdRemCBSReten(): ?string
    {
        $value = $this->get('adRemCBSReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemCBSReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemCBSReten' => $value]);
    }

    public function getQBCMonoReten(): ?string
    {
        $value = $this->get('qBCMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMonoReten' => $value]);
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
