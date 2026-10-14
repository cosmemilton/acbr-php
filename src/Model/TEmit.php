<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TEmit extends \Acbr\Document
{
    public const TYPE = 'TEmit';

    public function getCNAE(): ?string
    {
        $value = $this->get('CNAE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNAE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNAE' => $value]);
    }

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

    public function getCRT(): ?TpcnCRT
    {
        $value = $this->get('CRT');
        return $value === null ? null : TpcnCRT::from($value);
    }

    /** @param TpcnCRT|string $value Validated without scalar coercion. */
    public function withCRT(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CRT' => $value]);
    }

    public function getEnderEmit(): ?TenderEmit
    {
        $value = $this->get('EnderEmit');
        return $value === null ? null : new TenderEmit($value, $this->contract);
    }

    /** @param TenderEmit|array|\stdClass $value Validated without scalar coercion. */
    public function withEnderEmit(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['EnderEmit' => $value]);
    }

    public function getIE(): ?string
    {
        $value = $this->get('IE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IE' => $value]);
    }

    public function getIEST(): ?string
    {
        $value = $this->get('IEST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIEST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IEST' => $value]);
    }

    public function getIM(): ?string
    {
        $value = $this->get('IM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IM' => $value]);
    }

    public function getISUFEmit(): ?string
    {
        $value = $this->get('ISUFEmit');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withISUFEmit(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISUFEmit' => $value]);
    }

    public function getXFant(): ?string
    {
        $value = $this->get('xFant');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXFant(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xFant' => $value]);
    }

    public function getXNome(): ?string
    {
        $value = $this->get('xNome');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXNome(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xNome' => $value]);
    }
}
