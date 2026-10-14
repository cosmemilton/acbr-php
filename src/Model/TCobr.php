<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCobr extends \Acbr\Document
{
    public const TYPE = 'TCobr';

    public function getDup(): ?TDupCollection
    {
        $value = $this->get('Dup');
        return $value === null ? null : new TDupCollection($value, $this->contract);
    }

    /** @param TDupCollection|array $value Validated without scalar coercion. */
    public function withDup(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Dup' => $value]);
    }

    public function getFat(): ?TFat
    {
        $value = $this->get('Fat');
        return $value === null ? null : new TFat($value, $this->contract);
    }

    /** @param TFat|array|\stdClass $value Validated without scalar coercion. */
    public function withFat(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Fat' => $value]);
    }
}
