<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDICollectionItem extends \Acbr\Document
{
    public const TYPE = 'TDICollectionItem';

    public function getAdi(): ?TAdiCollection
    {
        $value = $this->get('adi');
        return $value === null ? null : new TAdiCollection($value, $this->contract);
    }

    /** @param TAdiCollection|array $value Validated without scalar coercion. */
    public function withAdi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adi' => $value]);
    }

    public function getCExportador(): ?string
    {
        $value = $this->get('cExportador');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCExportador(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cExportador' => $value]);
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

    public function getDDesemb(): ?string
    {
        $value = $this->get('dDesemb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDDesemb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dDesemb' => $value]);
    }

    public function getDDi(): ?string
    {
        $value = $this->get('dDi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDDi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dDi' => $value]);
    }

    public function getNDi(): ?string
    {
        $value = $this->get('nDi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNDi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nDi' => $value]);
    }

    public function getTpIntermedio(): ?TpcnTipoIntermedio
    {
        $value = $this->get('tpIntermedio');
        return $value === null ? null : TpcnTipoIntermedio::from($value);
    }

    /** @param TpcnTipoIntermedio|string $value Validated without scalar coercion. */
    public function withTpIntermedio(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpIntermedio' => $value]);
    }

    public function getTpViaTransp(): ?TpcnTipoViaTransp
    {
        $value = $this->get('tpViaTransp');
        return $value === null ? null : TpcnTipoViaTransp::from($value);
    }

    /** @param TpcnTipoViaTransp|string $value Validated without scalar coercion. */
    public function withTpViaTransp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpViaTransp' => $value]);
    }

    public function getUFDesemb(): ?string
    {
        $value = $this->get('UFDesemb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFDesemb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFDesemb' => $value]);
    }

    public function getUFTerceiro(): ?string
    {
        $value = $this->get('UFTerceiro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFTerceiro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFTerceiro' => $value]);
    }

    public function getVAFRMM(): ?string
    {
        $value = $this->get('vAFRMM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVAFRMM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vAFRMM' => $value]);
    }

    public function getXLocDesemb(): ?string
    {
        $value = $this->get('xLocDesemb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXLocDesemb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xLocDesemb' => $value]);
    }
}
