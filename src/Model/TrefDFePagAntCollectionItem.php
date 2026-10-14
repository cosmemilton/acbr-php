<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TrefDFePagAntCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TrefDFePagAntCollectionItem';

    public function getRefDFEChave(): ?string
    {
        $value = $this->get('refDFEChave');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRefDFEChave(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refDFEChave' => $value]);
    }
}
