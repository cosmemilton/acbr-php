<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TobsFiscoCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TobsFiscoCollectionItem';

    public function getXCampo(): ?string
    {
        $value = $this->get('xCampo');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXCampo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xCampo' => $value]);
    }

    public function getXTexto(): ?string
    {
        $value = $this->get('xTexto');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXTexto(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xTexto' => $value]);
    }
}
