<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TretTrib extends \Acbr\Document
{
    public const TYPE = 'TretTrib';

    public function getVBCIRRF(): ?string
    {
        $value = $this->get('vBCIRRF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCIRRF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCIRRF' => $value]);
    }

    public function getVBCRetPrev(): ?string
    {
        $value = $this->get('vBCRetPrev');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCRetPrev(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCRetPrev' => $value]);
    }

    public function getVIRRF(): ?string
    {
        $value = $this->get('vIRRF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIRRF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIRRF' => $value]);
    }

    public function getVRetCOFINS(): ?string
    {
        $value = $this->get('vRetCOFINS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRetCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRetCOFINS' => $value]);
    }

    public function getVRetCSLL(): ?string
    {
        $value = $this->get('vRetCSLL');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRetCSLL(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRetCSLL' => $value]);
    }

    public function getVRetPIS(): ?string
    {
        $value = $this->get('vRetPIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRetPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRetPIS' => $value]);
    }

    public function getVRetPrev(): ?string
    {
        $value = $this->get('vRetPrev');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRetPrev(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRetPrev' => $value]);
    }
}
