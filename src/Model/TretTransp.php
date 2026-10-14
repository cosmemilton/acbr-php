<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TretTransp extends \Acbr\Document
{
    public const TYPE = 'TretTransp';

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

    public function getPICMSRet(): ?string
    {
        $value = $this->get('pICMSRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPICMSRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pICMSRet' => $value]);
    }

    public function getVBCRet(): ?string
    {
        $value = $this->get('vBCRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCRet' => $value]);
    }

    public function getVICMSRet(): ?string
    {
        $value = $this->get('vICMSRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVICMSRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vICMSRet' => $value]);
    }

    public function getVServ(): ?string
    {
        $value = $this->get('vServ');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVServ(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vServ' => $value]);
    }
}
