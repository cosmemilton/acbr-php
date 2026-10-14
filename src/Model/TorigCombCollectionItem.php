<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TorigCombCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TorigCombCollectionItem';

    public function getCUFOrig(): ?int
    {
        $value = $this->get('cUFOrig');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCUFOrig(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cUFOrig' => $value]);
    }

    public function getIndImport(): ?TindImport
    {
        $value = $this->get('indImport');
        return $value === null ? null : TindImport::from($value);
    }

    /** @param TindImport|string $value Validated without scalar coercion. */
    public function withIndImport(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indImport' => $value]);
    }

    public function getPOrig(): ?string
    {
        $value = $this->get('pOrig');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPOrig(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pOrig' => $value]);
    }
}
