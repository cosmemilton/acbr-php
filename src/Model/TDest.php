<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDest extends \Acbr\Document
{
    public const TYPE = 'TDest';

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

    public function getEmail(): ?string
    {
        $value = $this->get('Email');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withEmail(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Email' => $value]);
    }

    public function getEnderDest(): ?TEnderDest
    {
        $value = $this->get('EnderDest');
        return $value === null ? null : new TEnderDest($value, $this->contract);
    }

    /** @param TEnderDest|array|\stdClass $value Validated without scalar coercion. */
    public function withEnderDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['EnderDest' => $value]);
    }

    public function getIdEstrangeiro(): ?string
    {
        $value = $this->get('idEstrangeiro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIdEstrangeiro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['idEstrangeiro' => $value]);
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

    public function getIM(): ?string
    {
        $value = $this->get('IM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IM' => $value]);
    }

    public function getIndIEDest(): ?TindIEDest
    {
        $value = $this->get('indIEDest');
        return $value === null ? null : TindIEDest::from($value);
    }

    /** @param TindIEDest|string $value Validated without scalar coercion. */
    public function withIndIEDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indIEDest' => $value]);
    }

    public function getISUF(): ?string
    {
        $value = $this->get('ISUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withISUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISUF' => $value]);
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
