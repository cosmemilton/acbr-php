<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TICMSUFDest extends \Acbr\Document
{
    public const TYPE = 'TICMSUFDest';

    public function getPFCPUFDest(): ?string
    {
        $value = $this->get('pFCPUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPFCPUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pFCPUFDest' => $value]);
    }

    public function getPICMSInter(): ?string
    {
        $value = $this->get('pICMSInter');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSInter(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSInter' => $value]);
    }

    public function getPICMSInterPart(): ?string
    {
        $value = $this->get('pICMSInterPart');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSInterPart(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSInterPart' => $value]);
    }

    public function getPICMSUFDest(): ?string
    {
        $value = $this->get('pICMSUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSUFDest' => $value]);
    }

    public function getVBCFCPUFDest(): ?string
    {
        $value = $this->get('vBCFCPUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCFCPUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCFCPUFDest' => $value]);
    }

    public function getVBCUFDest(): ?string
    {
        $value = $this->get('vBCUFDest');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCUFDest' => $value]);
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
}
