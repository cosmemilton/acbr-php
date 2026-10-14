<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TForDiaCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TForDiaCollectionItem';

    public function getDia(): ?int
    {
        $value = $this->get('dia');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withDia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dia' => $value]);
    }

    public function getQtde(): ?string
    {
        $value = $this->get('qtde');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQtde(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qtde' => $value]);
    }
}
