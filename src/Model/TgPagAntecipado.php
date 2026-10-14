<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgPagAntecipado extends \Acbr\Document
{
    public const TYPE = 'TgPagAntecipado';

    public function getRefNFe(): ?TrefDFePagAntCollection
    {
        $value = $this->get('refNFe');
        return $value === null ? null : new TrefDFePagAntCollection($value, $this->contract);
    }

    /** @param TrefDFePagAntCollection|array $value Validated without scalar coercion. */
    public function withRefNFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refNFe' => $value]);
    }
}
