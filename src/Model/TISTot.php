<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TISTot extends \Acbr\Document
{
    public const TYPE = 'TISTot';

    public function getVIS(): ?string
    {
        $value = $this->get('vIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIS' => $value]);
    }
}
