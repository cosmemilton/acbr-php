<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TinfNFe extends \Acbr\Document
{
    public const TYPE = 'TinfNFe';

    public function getID(): ?string
    {
        $value = $this->get('ID');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withID(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ID' => $value]);
    }

    public function getVersao(): ?string
    {
        $value = $this->get('Versao');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVersao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Versao' => $value]);
    }
}
