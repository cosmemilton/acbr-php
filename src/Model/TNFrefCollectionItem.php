<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TNFrefCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TNFrefCollectionItem';

    public function getRefCTe(): ?string
    {
        $value = $this->get('refCTe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRefCTe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refCTe' => $value]);
    }

    public function getRefECF(): ?TRefECF
    {
        $value = $this->get('RefECF');
        return $value === null ? null : new TRefECF($value, $this->contract);
    }

    /** @param TRefECF|array|\stdClass $value Validated without scalar coercion. */
    public function withRefECF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['RefECF' => $value]);
    }

    public function getRefNF(): ?TRefNF
    {
        $value = $this->get('RefNF');
        return $value === null ? null : new TRefNF($value, $this->contract);
    }

    /** @param TRefNF|array|\stdClass $value Validated without scalar coercion. */
    public function withRefNF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['RefNF' => $value]);
    }

    public function getRefNFe(): ?string
    {
        $value = $this->get('refNFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRefNFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refNFe' => $value]);
    }

    public function getRefNFeSig(): ?string
    {
        $value = $this->get('refNFeSig');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRefNFeSig(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refNFeSig' => $value]);
    }

    public function getRefNFP(): ?TRefNFP
    {
        $value = $this->get('RefNFP');
        return $value === null ? null : new TRefNFP($value, $this->contract);
    }

    /** @param TRefNFP|array|\stdClass $value Validated without scalar coercion. */
    public function withRefNFP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['RefNFP' => $value]);
    }
}
