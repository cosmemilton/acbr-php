<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TProcDFe extends \Acbr\Document
{
    public const TYPE = 'TProcDFe';

    public function getChDFe(): ?string
    {
        $value = $this->get('chDFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withChDFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['chDFe' => $value]);
    }

    public function getCMsg(): ?int
    {
        $value = $this->get('cMsg');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMsg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMsg' => $value]);
    }

    public function getCStat(): ?int
    {
        $value = $this->get('cStat');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCStat(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cStat' => $value]);
    }

    public function getDhRecbto(): ?string
    {
        $value = $this->get('dhRecbto');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDhRecbto(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dhRecbto' => $value]);
    }

    public function getDigVal(): ?string
    {
        $value = $this->get('digVal');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDigVal(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['digVal' => $value]);
    }

    public function getId(): ?string
    {
        $value = $this->get('Id');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withId(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Id' => $value]);
    }

    public function getNProt(): ?string
    {
        $value = $this->get('nProt');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNProt(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nProt' => $value]);
    }

    public function getPathDFe(): ?string
    {
        $value = $this->get('PathDFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPathDFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['PathDFe' => $value]);
    }

    public function getPathRetConsReciDFe(): ?string
    {
        $value = $this->get('PathRetConsReciDFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPathRetConsReciDFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['PathRetConsReciDFe' => $value]);
    }

    public function getPathRetConsSitDFe(): ?string
    {
        $value = $this->get('PathRetConsSitDFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPathRetConsSitDFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['PathRetConsSitDFe' => $value]);
    }

    public function getTpAmb(): ?TACBrTipoAmbiente
    {
        $value = $this->get('tpAmb');
        return $value === null ? null : TACBrTipoAmbiente::from($value);
    }

    /** @param TACBrTipoAmbiente|string $value Validated without scalar coercion. */
    public function withTpAmb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpAmb' => $value]);
    }

    public function getVerAplic(): ?string
    {
        $value = $this->get('verAplic');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVerAplic(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['verAplic' => $value]);
    }

    public function getXML_DFe(): ?string
    {
        $value = $this->get('XML_DFe');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXML_DFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['XML_DFe' => $value]);
    }

    public function getXML_prot(): ?string
    {
        $value = $this->get('XML_prot');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXML_prot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['XML_prot' => $value]);
    }

    public function getXMotivo(): ?string
    {
        $value = $this->get('xMotivo');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXMotivo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xMotivo' => $value]);
    }

    public function getXMsg(): ?string
    {
        $value = $this->get('xMsg');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXMsg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xMsg' => $value]);
    }
}
