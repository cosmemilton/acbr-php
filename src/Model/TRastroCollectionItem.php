<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TRastroCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TRastroCollectionItem';

    public function getCAgreg(): ?string
    {
        $value = $this->get('cAgreg');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCAgreg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cAgreg' => $value]);
    }

    public function getDFab(): ?string
    {
        $value = $this->get('dFab');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDFab(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dFab' => $value]);
    }

    public function getDVal(): ?string
    {
        $value = $this->get('dVal');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDVal(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dVal' => $value]);
    }

    public function getNLote(): ?string
    {
        $value = $this->get('nLote');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNLote(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nLote' => $value]);
    }

    public function getQLote(): ?string
    {
        $value = $this->get('qLote');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQLote(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qLote' => $value]);
    }
}
