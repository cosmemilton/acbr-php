<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetenIBSQtde extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetenIBSQtde';

    public function getAdRemIBSReten(): ?string
    {
        $value = $this->get('adRemIBSReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemIBSReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemIBSReten' => $value]);
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
