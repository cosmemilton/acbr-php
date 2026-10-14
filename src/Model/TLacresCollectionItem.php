<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TLacresCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TLacresCollectionItem';

    public function getNLacre(): ?string
    {
        $value = $this->get('nLacre');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNLacre(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nLacre' => $value]);
    }
}
