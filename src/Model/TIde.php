<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TIde extends \Acbr\Document
{
    public const TYPE = 'TIde';

    public function getCDV(): ?int
    {
        $value = $this->get('cDV');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCDV(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cDV' => $value]);
    }

    public function getCIndOp(): ?string
    {
        $value = $this->get('cIndOp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCIndOp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cIndOp' => $value]);
    }

    public function getCMunFG(): ?int
    {
        $value = $this->get('cMunFG');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMunFG(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMunFG' => $value]);
    }

    public function getCMunFGIBS(): ?int
    {
        $value = $this->get('cMunFGIBS');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMunFGIBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMunFGIBS' => $value]);
    }

    public function getCNF(): ?int
    {
        $value = $this->get('cNF');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCNF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cNF' => $value]);
    }

    public function getCUF(): ?int
    {
        $value = $this->get('cUF');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cUF' => $value]);
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

    public function getDhCont(): ?string
    {
        $value = $this->get('dhCont');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDhCont(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dhCont' => $value]);
    }

    public function getDPrevEntrega(): ?string
    {
        $value = $this->get('dPrevEntrega');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDPrevEntrega(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dPrevEntrega' => $value]);
    }

    public function getDSaiEnt(): ?string
    {
        $value = $this->get('dSaiEnt');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDSaiEnt(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dSaiEnt' => $value]);
    }

    public function getFinNFe(): ?TpcnFinalidadeNFe
    {
        $value = $this->get('finNFe');
        return $value === null ? null : TpcnFinalidadeNFe::from($value);
    }

    /** @param TpcnFinalidadeNFe|string $value Validated without scalar coercion. */
    public function withFinNFe(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['finNFe' => $value]);
    }

    public function getGCompraGov(): ?TgCompraGov
    {
        $value = $this->get('gCompraGov');
        return $value === null ? null : new TgCompraGov($value, $this->contract);
    }

    /** @param TgCompraGov|array|\stdClass $value Validated without scalar coercion. */
    public function withGCompraGov(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCompraGov' => $value]);
    }

    public function getGPagAntecipado(): ?TgPagAntecipado
    {
        $value = $this->get('gPagAntecipado');
        return $value === null ? null : new TgPagAntecipado($value, $this->contract);
    }

    /** @param TgPagAntecipado|array|\stdClass $value Validated without scalar coercion. */
    public function withGPagAntecipado(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gPagAntecipado' => $value]);
    }

    public function getHSaiEnt(): ?string
    {
        $value = $this->get('hSaiEnt');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withHSaiEnt(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['hSaiEnt' => $value]);
    }

    public function getIdDest(): ?TpcnDestinoOperacao
    {
        $value = $this->get('idDest');
        return $value === null ? null : TpcnDestinoOperacao::from($value);
    }

    /** @param TpcnDestinoOperacao|string $value Validated without scalar coercion. */
    public function withIdDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['idDest' => $value]);
    }

    public function getIndFinal(): ?TpcnConsumidorFinal
    {
        $value = $this->get('indFinal');
        return $value === null ? null : TpcnConsumidorFinal::from($value);
    }

    /** @param TpcnConsumidorFinal|string $value Validated without scalar coercion. */
    public function withIndFinal(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indFinal' => $value]);
    }

    public function getIndIntermed(): ?TindIntermed
    {
        $value = $this->get('indIntermed');
        return $value === null ? null : TindIntermed::from($value);
    }

    /** @param TindIntermed|string $value Validated without scalar coercion. */
    public function withIndIntermed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indIntermed' => $value]);
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

    public function getIndPres(): ?TpcnPresencaComprador
    {
        $value = $this->get('indPres');
        return $value === null ? null : TpcnPresencaComprador::from($value);
    }

    /** @param TpcnPresencaComprador|string $value Validated without scalar coercion. */
    public function withIndPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indPres' => $value]);
    }

    public function getModelo(): ?int
    {
        $value = $this->get('modelo');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withModelo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['modelo' => $value]);
    }

    public function getNatOp(): ?string
    {
        $value = $this->get('natOp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNatOp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['natOp' => $value]);
    }

    public function getNFref(): ?TNFrefCollection
    {
        $value = $this->get('NFref');
        return $value === null ? null : new TNFrefCollection($value, $this->contract);
    }

    /** @param TNFrefCollection|array $value Validated without scalar coercion. */
    public function withNFref(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['NFref' => $value]);
    }

    public function getNNF(): ?int
    {
        $value = $this->get('nNF');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNNF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nNF' => $value]);
    }

    public function getProcEmi(): ?TACBrProcessoEmissao
    {
        $value = $this->get('procEmi');
        return $value === null ? null : TACBrProcessoEmissao::from($value);
    }

    /** @param TACBrProcessoEmissao|string $value Validated without scalar coercion. */
    public function withProcEmi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['procEmi' => $value]);
    }

    public function getSerie(): ?int
    {
        $value = $this->get('serie');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withSerie(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['serie' => $value]);
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

    public function getTpEmis(): ?TACBrTipoEmissao
    {
        $value = $this->get('tpEmis');
        return $value === null ? null : TACBrTipoEmissao::from($value);
    }

    /** @param TACBrTipoEmissao|string $value Validated without scalar coercion. */
    public function withTpEmis(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpEmis' => $value]);
    }

    public function getTpImp(): ?TACBrTipoImpressao
    {
        $value = $this->get('tpImp');
        return $value === null ? null : TACBrTipoImpressao::from($value);
    }

    /** @param TACBrTipoImpressao|string $value Validated without scalar coercion. */
    public function withTpImp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpImp' => $value]);
    }

    public function getTpNF(): ?TTipoNFe
    {
        $value = $this->get('tpNF');
        return $value === null ? null : TTipoNFe::from($value);
    }

    /** @param TTipoNFe|string $value Validated without scalar coercion. */
    public function withTpNF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpNF' => $value]);
    }

    public function getTpNFCredito(): ?TtpNFCredito
    {
        $value = $this->get('tpNFCredito');
        return $value === null ? null : TtpNFCredito::from($value);
    }

    /** @param TtpNFCredito|string $value Validated without scalar coercion. */
    public function withTpNFCredito(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpNFCredito' => $value]);
    }

    public function getTpNFDebito(): ?TtpNFDebito
    {
        $value = $this->get('tpNFDebito');
        return $value === null ? null : TtpNFDebito::from($value);
    }

    /** @param TtpNFDebito|string $value Validated without scalar coercion. */
    public function withTpNFDebito(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpNFDebito' => $value]);
    }

    public function getVerProc(): ?string
    {
        $value = $this->get('verProc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVerProc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['verProc' => $value]);
    }

    public function getXJust(): ?string
    {
        $value = $this->get('xJust');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXJust(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xJust' => $value]);
    }
}
