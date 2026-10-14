<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TinfRespTec extends \Acbr\Document
{
    public const TYPE = 'TinfRespTec';

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

    public function getEmail(): ?string
    {
        $value = $this->get('email');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withEmail(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['email' => $value]);
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

    public function getHashCSRT(): ?string
    {
        $value = $this->get('hashCSRT');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withHashCSRT(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['hashCSRT' => $value]);
    }

    public function getIdCSRT(): ?int
    {
        $value = $this->get('idCSRT');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withIdCSRT(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['idCSRT' => $value]);
    }

    public function getXContato(): ?string
    {
        $value = $this->get('xContato');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXContato(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xContato' => $value]);
    }
}
