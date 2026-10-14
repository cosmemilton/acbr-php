<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TdefensivoCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TdefensivoCollectionItem';

    public function getCPFRespTec(): ?string
    {
        $value = $this->get('CPFRespTec');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCPFRespTec(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CPFRespTec' => $value]);
    }

    public function getNReceituario(): ?string
    {
        $value = $this->get('nReceituario');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNReceituario(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nReceituario' => $value]);
    }
}
