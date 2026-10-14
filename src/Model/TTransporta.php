<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TTransporta extends \Acbr\Document
{
    public const TYPE = 'TTransporta';

    public function getCNPJCPF(): ?string
    {
        $value = $this->get('CNPJCPF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCNPJCPF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CNPJCPF' => $value]);
    }

    public function getIE(): ?string
    {
        $value = $this->get('IE');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IE' => $value]);
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

    public function getXEnder(): ?string
    {
        $value = $this->get('xEnder');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXEnder(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xEnder' => $value]);
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

    public function getXNome(): ?string
    {
        $value = $this->get('xNome');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXNome(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xNome' => $value]);
    }
}
