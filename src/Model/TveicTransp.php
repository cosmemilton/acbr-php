<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TveicTransp extends \Acbr\Document
{
    public const TYPE = 'TveicTransp';

    public function getPlaca(): ?string
    {
        $value = $this->get('placa');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPlaca(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['placa' => $value]);
    }

    public function getRNTC(): ?string
    {
        $value = $this->get('RNTC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRNTC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['RNTC' => $value]);
    }

    public function getUF(): ?string
    {
        $value = $this->get('UF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UF' => $value]);
    }
}
