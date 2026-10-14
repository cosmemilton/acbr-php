<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TautXMLCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TautXMLCollectionItem';

    public function getCNPJCPF(): ?string
    {
        $value = $this->get('CNPJCPF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJCPF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJCPF' => $value]);
    }
}
