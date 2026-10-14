<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TNVECollectionItem extends \Acbr\Document
{
    public const TYPE = 'TNVECollectionItem';

    public function getNVE(): ?string
    {
        $value = $this->get('NVE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNVE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['NVE' => $value]);
    }
}
