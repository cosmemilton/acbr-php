<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TAvulsa extends \Acbr\Document
{
    public const TYPE = 'TAvulsa';

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

    public function getDEmi(): ?string
    {
        $value = $this->get('dEmi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDEmi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dEmi' => $value]);
    }

    public function getDPag(): ?string
    {
        $value = $this->get('dPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dPag' => $value]);
    }

    public function getFone(): ?string
    {
        $value = $this->get('fone');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withFone(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['fone' => $value]);
    }

    public function getMatr(): ?string
    {
        $value = $this->get('matr');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withMatr(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['matr' => $value]);
    }

    public function getNDAR(): ?string
    {
        $value = $this->get('nDAR');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNDAR(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nDAR' => $value]);
    }

    public function getRepEmi(): ?string
    {
        $value = $this->get('repEmi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRepEmi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['repEmi' => $value]);
    }

    public function getUF(): ?string
    {
        $value = $this->get('UF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UF' => $value]);
    }

    public function getVDAR(): ?string
    {
        $value = $this->get('vDAR');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDAR(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDAR' => $value]);
    }

    public function getXAgente(): ?string
    {
        $value = $this->get('xAgente');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXAgente(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xAgente' => $value]);
    }

    public function getXOrgao(): ?string
    {
        $value = $this->get('xOrgao');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXOrgao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xOrgao' => $value]);
    }
}
