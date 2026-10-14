<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TIPI extends \Acbr\Document
{
    public const TYPE = 'TIPI';

    public function getCEnq(): ?string
    {
        $value = $this->get('cEnq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCEnq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cEnq' => $value]);
    }

    public function getClEnq(): ?string
    {
        $value = $this->get('clEnq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withClEnq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['clEnq' => $value]);
    }

    public function getCNPJProd(): ?string
    {
        $value = $this->get('CNPJProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJProd' => $value]);
    }

    public function getCSelo(): ?string
    {
        $value = $this->get('cSelo');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCSelo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cSelo' => $value]);
    }

    public function getCST(): ?TpcnCstIpi
    {
        $value = $this->get('CST');
        return $value === null ? null : TpcnCstIpi::from($value);
    }

    /** @param TpcnCstIpi|string $value Validated without scalar coercion. */
    public function withCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CST' => $value]);
    }

    public function getPIPI(): ?string
    {
        $value = $this->get('pIPI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPIPI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pIPI' => $value]);
    }

    public function getQSelo(): ?int
    {
        $value = $this->get('qSelo');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withQSelo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qSelo' => $value]);
    }

    public function getQUnid(): ?string
    {
        $value = $this->get('qUnid');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQUnid(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qUnid' => $value]);
    }

    public function getVBC(): ?string
    {
        $value = $this->get('vBC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBC' => $value]);
    }

    public function getVIPI(): ?string
    {
        $value = $this->get('vIPI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIPI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIPI' => $value]);
    }

    public function getVUnid(): ?string
    {
        $value = $this->get('vUnid');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVUnid(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vUnid' => $value]);
    }
}
