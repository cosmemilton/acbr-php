<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMSTot extends \Acbr\Document
{
    public const TYPE = 'TICMSTot';

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

    public function getVCOFINS(): ?string
    {
        $value = $this->get('vCOFINS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCOFINS' => $value]);
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

    public function getVFCPUFDest(): ?string
    {
        $value = $this->get('vFCPUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFCPUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFCPUFDest' => $value]);
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

    public function getVICMSUFDest(): ?string
    {
        $value = $this->get('vICMSUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSUFDest' => $value]);
    }

    public function getVICMSUFRemet(): ?string
    {
        $value = $this->get('vICMSUFRemet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSUFRemet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSUFRemet' => $value]);
    }

    public function getVII(): ?string
    {
        $value = $this->get('vII');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVII(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vII' => $value]);
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

    public function getVIPIDevol(): ?string
    {
        $value = $this->get('vIPIDevol');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIPIDevol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIPIDevol' => $value]);
    }

    public function getVNF(): ?string
    {
        $value = $this->get('vNF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVNF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vNF' => $value]);
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

    public function getVPIS(): ?string
    {
        $value = $this->get('vPIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vPIS' => $value]);
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

    public function getVST(): ?string
    {
        $value = $this->get('vST');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vST' => $value]);
    }

    public function getVTotTrib(): ?string
    {
        $value = $this->get('vTotTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTotTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTotTrib' => $value]);
    }
}
