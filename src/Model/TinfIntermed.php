<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TinfIntermed extends \Acbr\Document
{
    public const TYPE = 'TinfIntermed';

    public function getCNPJ(): ?string
    {
        $value = $this->get('CNPJ');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJ(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJ' => $value]);
    }

    public function getIdCadIntTran(): ?string
    {
        $value = $this->get('idCadIntTran');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIdCadIntTran(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['idCadIntTran' => $value]);
    }
}
