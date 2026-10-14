<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class Tencerrante extends \Acbr\Document
{
    public const TYPE = 'Tencerrante';

    public function getNBico(): ?int
    {
        $value = $this->get('nBico');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNBico(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nBico' => $value]);
    }

    public function getNBomba(): ?int
    {
        $value = $this->get('nBomba');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNBomba(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nBomba' => $value]);
    }

    public function getNTanque(): ?int
    {
        $value = $this->get('nTanque');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withNTanque(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nTanque' => $value]);
    }

    public function getVEncFin(): ?string
    {
        $value = $this->get('vEncFin');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVEncFin(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vEncFin' => $value]);
    }

    public function getVEncIni(): ?string
    {
        $value = $this->get('vEncIni');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVEncIni(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vEncIni' => $value]);
    }
}
