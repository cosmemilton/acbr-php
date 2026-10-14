<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TdetExportCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TdetExportCollectionItem';

    public function getChNFe(): ?string
    {
        $value = $this->get('chNFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withChNFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['chNFe' => $value]);
    }

    public function getNDraw(): ?string
    {
        $value = $this->get('nDraw');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNDraw(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nDraw' => $value]);
    }

    public function getNRE(): ?string
    {
        $value = $this->get('nRE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNRE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nRE' => $value]);
    }

    public function getQExport(): ?string
    {
        $value = $this->get('qExport');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQExport(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qExport' => $value]);
    }
}
