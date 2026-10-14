<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TNFe extends \Acbr\Document
{
    public const TYPE = 'TNFe';

    public function getAgropecuario(): ?Tagropecuario
    {
        $value = $this->get('agropecuario');
        return $value === null ? null : new Tagropecuario($value, $this->contract);
    }

    /** @param Tagropecuario|array|\stdClass $value Validated without scalar coercion. */
    public function withAgropecuario(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['agropecuario' => $value]);
    }

    public function getAutXML(): ?TautXMLCollection
    {
        $value = $this->get('autXML');
        return $value === null ? null : new TautXMLCollection($value, $this->contract);
    }

    /** @param TautXMLCollection|array $value Validated without scalar coercion. */
    public function withAutXML(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['autXML' => $value]);
    }

    public function getAvulsa(): ?TAvulsa
    {
        $value = $this->get('Avulsa');
        return $value === null ? null : new TAvulsa($value, $this->contract);
    }

    /** @param TAvulsa|array|\stdClass $value Validated without scalar coercion. */
    public function withAvulsa(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Avulsa' => $value]);
    }

    public function getCana(): ?Tcana
    {
        $value = $this->get('cana');
        return $value === null ? null : new Tcana($value, $this->contract);
    }

    /** @param Tcana|array|\stdClass $value Validated without scalar coercion. */
    public function withCana(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cana' => $value]);
    }

    public function getCobr(): ?TCobr
    {
        $value = $this->get('Cobr');
        return $value === null ? null : new TCobr($value, $this->contract);
    }

    /** @param TCobr|array|\stdClass $value Validated without scalar coercion. */
    public function withCobr(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Cobr' => $value]);
    }

    public function getCompra(): ?TCompra
    {
        $value = $this->get('compra');
        return $value === null ? null : new TCompra($value, $this->contract);
    }

    /** @param TCompra|array|\stdClass $value Validated without scalar coercion. */
    public function withCompra(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['compra' => $value]);
    }

    public function getDest(): ?TDest
    {
        $value = $this->get('Dest');
        return $value === null ? null : new TDest($value, $this->contract);
    }

    /** @param TDest|array|\stdClass $value Validated without scalar coercion. */
    public function withDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Dest' => $value]);
    }

    public function getDet(): ?TDetCollection
    {
        $value = $this->get('Det');
        return $value === null ? null : new TDetCollection($value, $this->contract);
    }

    /** @param TDetCollection|array $value Validated without scalar coercion. */
    public function withDet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Det' => $value]);
    }

    public function getEmit(): ?TEmit
    {
        $value = $this->get('Emit');
        return $value === null ? null : new TEmit($value, $this->contract);
    }

    /** @param TEmit|array|\stdClass $value Validated without scalar coercion. */
    public function withEmit(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Emit' => $value]);
    }

    public function getEntrega(): ?TEntrega
    {
        $value = $this->get('Entrega');
        return $value === null ? null : new TEntrega($value, $this->contract);
    }

    /** @param TEntrega|array|\stdClass $value Validated without scalar coercion. */
    public function withEntrega(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Entrega' => $value]);
    }

    public function getExporta(): ?TExporta
    {
        $value = $this->get('exporta');
        return $value === null ? null : new TExporta($value, $this->contract);
    }

    /** @param TExporta|array|\stdClass $value Validated without scalar coercion. */
    public function withExporta(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['exporta' => $value]);
    }

    public function getIde(): ?TIde
    {
        $value = $this->get('Ide');
        return $value === null ? null : new TIde($value, $this->contract);
    }

    /** @param TIde|array|\stdClass $value Validated without scalar coercion. */
    public function withIde(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Ide' => $value]);
    }

    public function getInfAdic(): ?TInfAdic
    {
        $value = $this->get('InfAdic');
        return $value === null ? null : new TInfAdic($value, $this->contract);
    }

    /** @param TInfAdic|array|\stdClass $value Validated without scalar coercion. */
    public function withInfAdic(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['InfAdic' => $value]);
    }

    public function getInfIntermed(): ?TinfIntermed
    {
        $value = $this->get('infIntermed');
        return $value === null ? null : new TinfIntermed($value, $this->contract);
    }

    /** @param TinfIntermed|array|\stdClass $value Validated without scalar coercion. */
    public function withInfIntermed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infIntermed' => $value]);
    }

    public function getInfNFe(): ?TinfNFe
    {
        $value = $this->get('infNFe');
        return $value === null ? null : new TinfNFe($value, $this->contract);
    }

    /** @param TinfNFe|array|\stdClass $value Validated without scalar coercion. */
    public function withInfNFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infNFe' => $value]);
    }

    public function getInfNFeSupl(): ?TinfNFeSupl
    {
        $value = $this->get('infNFeSupl');
        return $value === null ? null : new TinfNFeSupl($value, $this->contract);
    }

    public function getInfPAA(): ?TinfPAA
    {
        $value = $this->get('infPAA');
        return $value === null ? null : new TinfPAA($value, $this->contract);
    }

    /** @param TinfPAA|array|\stdClass $value Validated without scalar coercion. */
    public function withInfPAA(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infPAA' => $value]);
    }

    public function getInfRespTec(): ?TinfRespTec
    {
        $value = $this->get('infRespTec');
        return $value === null ? null : new TinfRespTec($value, $this->contract);
    }

    /** @param TinfRespTec|array|\stdClass $value Validated without scalar coercion. */
    public function withInfRespTec(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infRespTec' => $value]);
    }

    public function getPag(): ?TpagCollection
    {
        $value = $this->get('pag');
        return $value === null ? null : new TpagCollection($value, $this->contract);
    }

    /** @param TpagCollection|array $value Validated without scalar coercion. */
    public function withPag(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pag' => $value]);
    }

    public function getProcNFe(): ?TProcDFe
    {
        $value = $this->get('procNFe');
        return $value === null ? null : new TProcDFe($value, $this->contract);
    }

    public function getRetirada(): ?TRetirada
    {
        $value = $this->get('Retirada');
        return $value === null ? null : new TRetirada($value, $this->contract);
    }

    /** @param TRetirada|array|\stdClass $value Validated without scalar coercion. */
    public function withRetirada(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Retirada' => $value]);
    }

    public function getSignature(): ?TSignature
    {
        $value = $this->get('signature');
        return $value === null ? null : new TSignature($value, $this->contract);
    }

    public function getTotal(): ?TTotal
    {
        $value = $this->get('Total');
        return $value === null ? null : new TTotal($value, $this->contract);
    }

    /** @param TTotal|array|\stdClass $value Validated without scalar coercion. */
    public function withTotal(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Total' => $value]);
    }

    public function getTransp(): ?TTransp
    {
        $value = $this->get('Transp');
        return $value === null ? null : new TTransp($value, $this->contract);
    }

    /** @param TTransp|array|\stdClass $value Validated without scalar coercion. */
    public function withTransp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Transp' => $value]);
    }
}
