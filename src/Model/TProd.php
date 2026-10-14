<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TProd extends \Acbr\Document
{
    public const TYPE = 'TProd';

    public function getArma(): ?TArmaCollection
    {
        $value = $this->get('arma');
        return $value === null ? null : new TArmaCollection($value, $this->contract);
    }

    /** @param TArmaCollection|array $value Validated without scalar coercion. */
    public function withArma(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['arma' => $value]);
    }

    public function getCBarra(): ?string
    {
        $value = $this->get('cBarra');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCBarra(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cBarra' => $value]);
    }

    public function getCBarraTrib(): ?string
    {
        $value = $this->get('cBarraTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCBarraTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cBarraTrib' => $value]);
    }

    public function getCBenef(): ?string
    {
        $value = $this->get('cBenef');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCBenef(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cBenef' => $value]);
    }

    public function getCEAN(): ?string
    {
        $value = $this->get('cEAN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCEAN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cEAN' => $value]);
    }

    public function getCEANTrib(): ?string
    {
        $value = $this->get('cEANTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCEANTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cEANTrib' => $value]);
    }

    public function getCEST(): ?string
    {
        $value = $this->get('CEST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCEST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CEST' => $value]);
    }

    public function getCFOP(): ?string
    {
        $value = $this->get('CFOP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCFOP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CFOP' => $value]);
    }

    public function getCNPJFab(): ?string
    {
        $value = $this->get('CNPJFab');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJFab(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJFab' => $value]);
    }

    public function getComb(): ?TComb
    {
        $value = $this->get('comb');
        return $value === null ? null : new TComb($value, $this->contract);
    }

    /** @param TComb|array|\stdClass $value Validated without scalar coercion. */
    public function withComb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['comb' => $value]);
    }

    public function getCProd(): ?string
    {
        $value = $this->get('cProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cProd' => $value]);
    }

    public function getCredPresumido(): ?TCredPresumidoCollection
    {
        $value = $this->get('CredPresumido');
        return $value === null ? null : new TCredPresumidoCollection($value, $this->contract);
    }

    /** @param TCredPresumidoCollection|array $value Validated without scalar coercion. */
    public function withCredPresumido(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CredPresumido' => $value]);
    }

    public function getDetExport(): ?TdetExportCollection
    {
        $value = $this->get('detExport');
        return $value === null ? null : new TdetExportCollection($value, $this->contract);
    }

    /** @param TdetExportCollection|array $value Validated without scalar coercion. */
    public function withDetExport(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['detExport' => $value]);
    }

    public function getDI(): ?TDICollection
    {
        $value = $this->get('DI');
        return $value === null ? null : new TDICollection($value, $this->contract);
    }

    /** @param TDICollection|array $value Validated without scalar coercion. */
    public function withDI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['DI' => $value]);
    }

    public function getEXTIPI(): ?string
    {
        $value = $this->get('EXTIPI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withEXTIPI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['EXTIPI' => $value]);
    }

    public function getIndBemMovelUsado(): ?TIndicadorEx
    {
        $value = $this->get('indBemMovelUsado');
        return $value === null ? null : TIndicadorEx::from($value);
    }

    /** @param TIndicadorEx|string $value Validated without scalar coercion. */
    public function withIndBemMovelUsado(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indBemMovelUsado' => $value]);
    }

    public function getIndEscala(): ?TpcnIndEscala
    {
        $value = $this->get('indEscala');
        return $value === null ? null : TpcnIndEscala::from($value);
    }

    /** @param TpcnIndEscala|string $value Validated without scalar coercion. */
    public function withIndEscala(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indEscala' => $value]);
    }

    public function getIndTot(): ?TpcnIndicadorTotal
    {
        $value = $this->get('IndTot');
        return $value === null ? null : TpcnIndicadorTotal::from($value);
    }

    /** @param TpcnIndicadorTotal|string $value Validated without scalar coercion. */
    public function withIndTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IndTot' => $value]);
    }

    public function getMed(): ?TMedCollection
    {
        $value = $this->get('med');
        return $value === null ? null : new TMedCollection($value, $this->contract);
    }

    /** @param TMedCollection|array $value Validated without scalar coercion. */
    public function withMed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['med' => $value]);
    }

    public function getNCM(): ?string
    {
        $value = $this->get('NCM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNCM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['NCM' => $value]);
    }

    public function getNFCI(): ?string
    {
        $value = $this->get('nFCI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNFCI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nFCI' => $value]);
    }

    public function getNItem(): ?int
    {
        $value = $this->get('nItem');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNItem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nItem' => $value]);
    }

    public function getNItemPed(): ?string
    {
        $value = $this->get('nItemPed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNItemPed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nItemPed' => $value]);
    }

    public function getNRECOPI(): ?string
    {
        $value = $this->get('nRECOPI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNRECOPI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nRECOPI' => $value]);
    }

    public function getNVE(): ?TNVECollection
    {
        $value = $this->get('NVE');
        return $value === null ? null : new TNVECollection($value, $this->contract);
    }

    /** @param TNVECollection|array $value Validated without scalar coercion. */
    public function withNVE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['NVE' => $value]);
    }

    public function getQCom(): ?string
    {
        $value = $this->get('qCom');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQCom(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qCom' => $value]);
    }

    public function getQTrib(): ?string
    {
        $value = $this->get('qTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTrib' => $value]);
    }

    public function getRastro(): ?TRastroCollection
    {
        $value = $this->get('rastro');
        return $value === null ? null : new TRastroCollection($value, $this->contract);
    }

    /** @param TRastroCollection|array $value Validated without scalar coercion. */
    public function withRastro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['rastro' => $value]);
    }

    public function getTpCredPresIBSZFM(): ?TTpCredPresIBSZFM
    {
        $value = $this->get('tpCredPresIBSZFM');
        return $value === null ? null : TTpCredPresIBSZFM::from($value);
    }

    /** @param TTpCredPresIBSZFM|string $value Validated without scalar coercion. */
    public function withTpCredPresIBSZFM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpCredPresIBSZFM' => $value]);
    }

    public function getUCom(): ?string
    {
        $value = $this->get('uCom');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUCom(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['uCom' => $value]);
    }

    public function getUTrib(): ?string
    {
        $value = $this->get('uTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['uTrib' => $value]);
    }

    public function getVDesc(): ?string
    {
        $value = $this->get('vDesc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDesc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDesc' => $value]);
    }

    public function getVeicProd(): ?TveicProd
    {
        $value = $this->get('veicProd');
        return $value === null ? null : new TveicProd($value, $this->contract);
    }

    /** @param TveicProd|array|\stdClass $value Validated without scalar coercion. */
    public function withVeicProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['veicProd' => $value]);
    }

    public function getVFrete(): ?string
    {
        $value = $this->get('vFrete');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFrete(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFrete' => $value]);
    }

    public function getVOutro(): ?string
    {
        $value = $this->get('vOutro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVOutro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vOutro' => $value]);
    }

    public function getVProd(): ?string
    {
        $value = $this->get('vProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vProd' => $value]);
    }

    public function getVSeg(): ?string
    {
        $value = $this->get('vSeg');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVSeg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vSeg' => $value]);
    }

    public function getVUnCom(): ?string
    {
        $value = $this->get('vUnCom');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVUnCom(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vUnCom' => $value]);
    }

    public function getVUnTrib(): ?string
    {
        $value = $this->get('vUnTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVUnTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vUnTrib' => $value]);
    }

    public function getXPed(): ?string
    {
        $value = $this->get('xPed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXPed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xPed' => $value]);
    }

    public function getXProd(): ?string
    {
        $value = $this->get('xProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xProd' => $value]);
    }
}
