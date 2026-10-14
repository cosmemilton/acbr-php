<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDeducCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TDeducCollectionItem';

    public function getVDed(): ?string
    {
        $value = $this->get('vDed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDed' => $value]);
    }

    public function getXDed(): ?string
    {
        $value = $this->get('xDed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXDed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xDed' => $value]);
    }
}
