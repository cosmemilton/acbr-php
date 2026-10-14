<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TArmaCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TArmaCollectionItem';

    public function getDescr(): ?string
    {
        $value = $this->get('descr');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDescr(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['descr' => $value]);
    }

    public function getNCano(): ?string
    {
        $value = $this->get('nCano');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNCano(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nCano' => $value]);
    }

    public function getNSerie(): ?string
    {
        $value = $this->get('nSerie');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNSerie(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nSerie' => $value]);
    }

    public function getTpArma(): ?TpcnTipoArma
    {
        $value = $this->get('tpArma');
        return $value === null ? null : TpcnTipoArma::from($value);
    }

    /** @param TpcnTipoArma|string $value Validated without scalar coercion. */
    public function withTpArma(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpArma' => $value]);
    }
}
