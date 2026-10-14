<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TenderEmit extends \Acbr\Document
{
    public const TYPE = 'TenderEmit';

    public function getCEP(): ?int
    {
        $value = $this->get('CEP');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCEP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CEP' => $value]);
    }

    public function getCMun(): ?int
    {
        $value = $this->get('cMun');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMun' => $value]);
    }

    public function getCPais(): ?int
    {
        $value = $this->get('cPais');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCPais(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cPais' => $value]);
    }

    public function getFone(): ?string
    {
        $value = $this->get('fone');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withFone(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['fone' => $value]);
    }

    public function getNro(): ?string
    {
        $value = $this->get('nro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nro' => $value]);
    }

    public function getUF(): ?string
    {
        $value = $this->get('UF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UF' => $value]);
    }

    public function getXBairro(): ?string
    {
        $value = $this->get('xBairro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXBairro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xBairro' => $value]);
    }

    public function getXCpl(): ?string
    {
        $value = $this->get('xCpl');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXCpl(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xCpl' => $value]);
    }

    public function getXLgr(): ?string
    {
        $value = $this->get('xLgr');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXLgr(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xLgr' => $value]);
    }

    public function getXMun(): ?string
    {
        $value = $this->get('xMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xMun' => $value]);
    }

    public function getXPais(): ?string
    {
        $value = $this->get('xPais');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXPais(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xPais' => $value]);
    }
}
