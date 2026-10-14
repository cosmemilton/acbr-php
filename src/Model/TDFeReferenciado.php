<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDFeReferenciado extends \Acbr\Document
{
    public const TYPE = 'TDFeReferenciado';

    public function getChaveAcesso(): ?string
    {
        $value = $this->get('chaveAcesso');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withChaveAcesso(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['chaveAcesso' => $value]);
    }

    public function getNItem(): ?int
    {
        $value = $this->get('nItem');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNItem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nItem' => $value]);
    }
}
