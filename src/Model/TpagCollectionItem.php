<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TpagCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TpagCollectionItem';

    public function getCAut(): ?string
    {
        $value = $this->get('cAut');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCAut(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cAut' => $value]);
    }

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

    public function getCNPJPag(): ?string
    {
        $value = $this->get('CNPJPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJPag' => $value]);
    }

    public function getCNPJReceb(): ?string
    {
        $value = $this->get('CNPJReceb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJReceb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJReceb' => $value]);
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

    public function getIdTermPag(): ?string
    {
        $value = $this->get('idTermPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIdTermPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['idTermPag' => $value]);
    }

    public function getIndPag(): ?TpcnIndicadorPagamento
    {
        $value = $this->get('indPag');
        return $value === null ? null : TpcnIndicadorPagamento::from($value);
    }

    /** @param TpcnIndicadorPagamento|string $value Validated without scalar coercion. */
    public function withIndPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indPag' => $value]);
    }

    public function getTBand(): ?TpcnBandeiraCartao
    {
        $value = $this->get('tBand');
        return $value === null ? null : TpcnBandeiraCartao::from($value);
    }

    /** @param TpcnBandeiraCartao|string $value Validated without scalar coercion. */
    public function withTBand(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tBand' => $value]);
    }

    public function getTPag(): ?TpcnFormaPagamento
    {
        $value = $this->get('tPag');
        return $value === null ? null : TpcnFormaPagamento::from($value);
    }

    /** @param TpcnFormaPagamento|string $value Validated without scalar coercion. */
    public function withTPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tPag' => $value]);
    }

    public function getTpIntegra(): ?TtpIntegra
    {
        $value = $this->get('tpIntegra');
        return $value === null ? null : TtpIntegra::from($value);
    }

    /** @param TtpIntegra|string $value Validated without scalar coercion. */
    public function withTpIntegra(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpIntegra' => $value]);
    }

    public function getUFPag(): ?string
    {
        $value = $this->get('UFPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFPag' => $value]);
    }

    public function getVPag(): ?string
    {
        $value = $this->get('vPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vPag' => $value]);
    }

    public function getXPag(): ?string
    {
        $value = $this->get('xPag');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xPag' => $value]);
    }
}
