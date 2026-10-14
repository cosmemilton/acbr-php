<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMS extends \Acbr\Document
{
    public const TYPE = 'TICMS';

    public function getAdRemICMS(): ?string
    {
        $value = $this->get('adRemICMS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemICMS' => $value]);
    }

    public function getAdRemICMSDif(): ?string
    {
        $value = $this->get('adRemICMSDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemICMSDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemICMSDif' => $value]);
    }

    public function getAdRemICMSRet(): ?string
    {
        $value = $this->get('adRemICMSRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemICMSRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemICMSRet' => $value]);
    }

    public function getAdRemICMSReten(): ?string
    {
        $value = $this->get('adRemICMSReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemICMSReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemICMSReten' => $value]);
    }

    public function getCBenefRBC(): ?string
    {
        $value = $this->get('cBenefRBC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCBenefRBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cBenefRBC' => $value]);
    }

    public function getCSOSN(): ?TCSOSNIcms
    {
        $value = $this->get('CSOSN');
        return $value === null ? null : TCSOSNIcms::from($value);
    }

    /** @param TCSOSNIcms|string $value Validated without scalar coercion. */
    public function withCSOSN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CSOSN' => $value]);
    }

    public function getCST(): ?TCSTIcms
    {
        $value = $this->get('CST');
        return $value === null ? null : TCSTIcms::from($value);
    }

    /** @param TCSTIcms|string $value Validated without scalar coercion. */
    public function withCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CST' => $value]);
    }

    public function getIndDeduzDeson(): ?TIndicadorEx
    {
        $value = $this->get('indDeduzDeson');
        return $value === null ? null : TIndicadorEx::from($value);
    }

    /** @param TIndicadorEx|string $value Validated without scalar coercion. */
    public function withIndDeduzDeson(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indDeduzDeson' => $value]);
    }

    public function getModBC(): ?TpcnDeterminacaoBaseIcms
    {
        $value = $this->get('modBC');
        return $value === null ? null : TpcnDeterminacaoBaseIcms::from($value);
    }

    /** @param TpcnDeterminacaoBaseIcms|string $value Validated without scalar coercion. */
    public function withModBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['modBC' => $value]);
    }

    public function getModBCST(): ?TpcnDeterminacaoBaseIcmsST
    {
        $value = $this->get('modBCST');
        return $value === null ? null : TpcnDeterminacaoBaseIcmsST::from($value);
    }

    /** @param TpcnDeterminacaoBaseIcmsST|string $value Validated without scalar coercion. */
    public function withModBCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['modBCST' => $value]);
    }

    public function getMotDesICMS(): ?TpcnMotivoDesoneracaoICMS
    {
        $value = $this->get('motDesICMS');
        return $value === null ? null : TpcnMotivoDesoneracaoICMS::from($value);
    }

    /** @param TpcnMotivoDesoneracaoICMS|string $value Validated without scalar coercion. */
    public function withMotDesICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['motDesICMS' => $value]);
    }

    public function getMotDesICMSST(): ?TpcnMotivoDesoneracaoICMS
    {
        $value = $this->get('motDesICMSST');
        return $value === null ? null : TpcnMotivoDesoneracaoICMS::from($value);
    }

    /** @param TpcnMotivoDesoneracaoICMS|string $value Validated without scalar coercion. */
    public function withMotDesICMSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['motDesICMSST' => $value]);
    }

    public function getMotRedAdRem(): ?TmotRedAdRem
    {
        $value = $this->get('motRedAdRem');
        return $value === null ? null : TmotRedAdRem::from($value);
    }

    /** @param TmotRedAdRem|string $value Validated without scalar coercion. */
    public function withMotRedAdRem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['motRedAdRem' => $value]);
    }

    public function getOrig(): ?TOrigemMercadoria
    {
        $value = $this->get('orig');
        return $value === null ? null : TOrigemMercadoria::from($value);
    }

    /** @param TOrigemMercadoria|string $value Validated without scalar coercion. */
    public function withOrig(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['orig' => $value]);
    }

    public function getPBCOp(): ?string
    {
        $value = $this->get('pBCOp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPBCOp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pBCOp' => $value]);
    }

    public function getPCredSN(): ?string
    {
        $value = $this->get('pCredSN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPCredSN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pCredSN' => $value]);
    }

    public function getPDif(): ?string
    {
        $value = $this->get('pDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pDif' => $value]);
    }

    public function getPFCP(): ?string
    {
        $value = $this->get('pFCP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPFCP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pFCP' => $value]);
    }

    public function getPFCPDif(): ?string
    {
        $value = $this->get('pFCPDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPFCPDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pFCPDif' => $value]);
    }

    public function getPFCPST(): ?string
    {
        $value = $this->get('pFCPST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPFCPST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pFCPST' => $value]);
    }

    public function getPFCPSTRet(): ?string
    {
        $value = $this->get('pFCPSTRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPFCPSTRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pFCPSTRet' => $value]);
    }

    public function getPICMS(): ?string
    {
        $value = $this->get('pICMS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMS' => $value]);
    }

    public function getPICMSEfet(): ?string
    {
        $value = $this->get('pICMSEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSEfet' => $value]);
    }

    public function getPICMSST(): ?string
    {
        $value = $this->get('pICMSST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSST' => $value]);
    }

    public function getPMVAST(): ?string
    {
        $value = $this->get('pMVAST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPMVAST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pMVAST' => $value]);
    }

    public function getPRedAdRem(): ?string
    {
        $value = $this->get('pRedAdRem');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedAdRem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedAdRem' => $value]);
    }

    public function getPRedBC(): ?string
    {
        $value = $this->get('pRedBC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedBC' => $value]);
    }

    public function getPRedBCEfet(): ?string
    {
        $value = $this->get('pRedBCEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedBCEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedBCEfet' => $value]);
    }

    public function getPRedBCST(): ?string
    {
        $value = $this->get('pRedBCST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedBCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedBCST' => $value]);
    }

    public function getPST(): ?string
    {
        $value = $this->get('pST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pST' => $value]);
    }

    public function getQBCMono(): ?string
    {
        $value = $this->get('qBCMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMono' => $value]);
    }

    public function getQBCMonoDif(): ?string
    {
        $value = $this->get('qBCMonoDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMonoDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMonoDif' => $value]);
    }

    public function getQBCMonoRet(): ?string
    {
        $value = $this->get('qBCMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMonoRet' => $value]);
    }

    public function getQBCMonoReten(): ?string
    {
        $value = $this->get('qBCMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMonoReten' => $value]);
    }

    public function getUFST(): ?string
    {
        $value = $this->get('UFST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFST' => $value]);
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

    public function getVBCEfet(): ?string
    {
        $value = $this->get('vBCEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCEfet' => $value]);
    }

    public function getVBCFCP(): ?string
    {
        $value = $this->get('vBCFCP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCFCP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCFCP' => $value]);
    }

    public function getVBCFCPST(): ?string
    {
        $value = $this->get('vBCFCPST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCFCPST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCFCPST' => $value]);
    }

    public function getVBCFCPSTRet(): ?string
    {
        $value = $this->get('vBCFCPSTRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCFCPSTRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCFCPSTRet' => $value]);
    }

    public function getVBCST(): ?string
    {
        $value = $this->get('vBCST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCST' => $value]);
    }

    public function getVBCSTDest(): ?string
    {
        $value = $this->get('vBCSTDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCSTDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCSTDest' => $value]);
    }

    public function getVBCSTRet(): ?string
    {
        $value = $this->get('vBCSTRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCSTRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCSTRet' => $value]);
    }

    public function getVCredICMSSN(): ?string
    {
        $value = $this->get('vCredICMSSN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredICMSSN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredICMSSN' => $value]);
    }

    public function getVFCP(): ?string
    {
        $value = $this->get('vFCP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCP' => $value]);
    }

    public function getVFCPDif(): ?string
    {
        $value = $this->get('vFCPDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCPDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCPDif' => $value]);
    }

    public function getVFCPEfet(): ?string
    {
        $value = $this->get('vFCPEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCPEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCPEfet' => $value]);
    }

    public function getVFCPST(): ?string
    {
        $value = $this->get('vFCPST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCPST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCPST' => $value]);
    }

    public function getVFCPSTRet(): ?string
    {
        $value = $this->get('vFCPSTRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCPSTRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCPSTRet' => $value]);
    }

    public function getVICMS(): ?string
    {
        $value = $this->get('vICMS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMS' => $value]);
    }

    public function getVICMSDeson(): ?string
    {
        $value = $this->get('vICMSDeson');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSDeson(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSDeson' => $value]);
    }

    public function getVICMSDif(): ?string
    {
        $value = $this->get('vICMSDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSDif' => $value]);
    }

    public function getVICMSEfet(): ?string
    {
        $value = $this->get('vICMSEfet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSEfet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSEfet' => $value]);
    }

    public function getVICMSMono(): ?string
    {
        $value = $this->get('vICMSMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSMono' => $value]);
    }

    public function getVICMSMonoDif(): ?string
    {
        $value = $this->get('vICMSMonoDif');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSMonoDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSMonoDif' => $value]);
    }

    public function getVICMSMonoOp(): ?string
    {
        $value = $this->get('vICMSMonoOp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSMonoOp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSMonoOp' => $value]);
    }

    public function getVICMSMonoRet(): ?string
    {
        $value = $this->get('vICMSMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSMonoRet' => $value]);
    }

    public function getVICMSMonoReten(): ?string
    {
        $value = $this->get('vICMSMonoReten');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSMonoReten(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSMonoReten' => $value]);
    }

    public function getVICMSOp(): ?string
    {
        $value = $this->get('vICMSOp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSOp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSOp' => $value]);
    }

    public function getVICMSST(): ?string
    {
        $value = $this->get('vICMSST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSST' => $value]);
    }

    public function getVICMSSTDeson(): ?string
    {
        $value = $this->get('vICMSSTDeson');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSTDeson(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSTDeson' => $value]);
    }

    public function getVICMSSTDest(): ?string
    {
        $value = $this->get('vICMSSTDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSTDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSTDest' => $value]);
    }

    public function getVICMSSTRet(): ?string
    {
        $value = $this->get('vICMSSTRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSTRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSTRet' => $value]);
    }

    public function getVICMSSubstituto(): ?string
    {
        $value = $this->get('vICMSSubstituto');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSSubstituto(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSSubstituto' => $value]);
    }
}
