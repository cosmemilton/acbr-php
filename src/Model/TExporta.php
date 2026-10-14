<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TExporta extends \Acbr\Document
{
    public const TYPE = 'TExporta';

    public function getUFembarq(): ?string
    {
        $value = $this->get('UFembarq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFembarq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFembarq' => $value]);
    }

    public function getUFSaidaPais(): ?string
    {
        $value = $this->get('UFSaidaPais');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFSaidaPais(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFSaidaPais' => $value]);
    }

    public function getXLocDespacho(): ?string
    {
        $value = $this->get('xLocDespacho');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXLocDespacho(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xLocDespacho' => $value]);
    }

    public function getXLocEmbarq(): ?string
    {
        $value = $this->get('xLocEmbarq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXLocEmbarq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xLocEmbarq' => $value]);
    }

    public function getXLocExporta(): ?string
    {
        $value = $this->get('xLocExporta');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXLocExporta(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xLocExporta' => $value]);
    }
}
