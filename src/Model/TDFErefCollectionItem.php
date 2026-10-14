<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDFErefCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TDFErefCollectionItem';

    public function getRefDFeChave(): ?string
    {
        $value = $this->get('refDFeChave');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRefDFeChave(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refDFeChave' => $value]);
    }
}
