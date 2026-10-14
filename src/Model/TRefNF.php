<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TRefNF extends \Acbr\Document
{
    public const TYPE = 'TRefNF';

    public function getAAMM(): ?string
    {
        $value = $this->get('AAMM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAAMM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['AAMM' => $value]);
    }

    public function getCNPJ(): ?string
    {
        $value = $this->get('CNPJ');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJ(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJ' => $value]);
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
}
