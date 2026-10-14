<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TinfPAA extends \Acbr\Document
{
    public const TYPE = 'TinfPAA';

    public function getCNPJPAA(): ?string
    {
        $value = $this->get('CNPJPAA');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJPAA(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJPAA' => $value]);
    }

    public function getExponent(): ?string
    {
        $value = $this->get('Exponent');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withExponent(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Exponent' => $value]);
    }

    public function getModulus(): ?string
    {
        $value = $this->get('Modulus');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withModulus(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Modulus' => $value]);
    }

    public function getSignatureValue(): ?string
    {
        $value = $this->get('SignatureValue');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withSignatureValue(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['SignatureValue' => $value]);
    }
}
