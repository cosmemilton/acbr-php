<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDupCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TDupCollectionItem';

    public function getDVenc(): ?string
    {
        $value = $this->get('dVenc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDVenc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dVenc' => $value]);
    }

    public function getNDup(): ?string
    {
        $value = $this->get('nDup');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNDup(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nDup' => $value]);
    }

    public function getVDup(): ?string
    {
        $value = $this->get('vDup');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDup(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDup' => $value]);
    }
}
