<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgTribCompraGov extends \Acbr\Document
{
    public const TYPE = 'TgTribCompraGov';

    public function getPAliqCBS(): ?string
    {
        $value = $this->get('pAliqCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqCBS' => $value]);
    }

    public function getPAliqIBSMun(): ?string
    {
        $value = $this->get('pAliqIBSMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqIBSMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqIBSMun' => $value]);
    }

    public function getPAliqIBSUF(): ?string
    {
        $value = $this->get('pAliqIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqIBSUF' => $value]);
    }

    public function getVTribCBS(): ?string
    {
        $value = $this->get('vTribCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribCBS' => $value]);
    }

    public function getVTribIBSMun(): ?string
    {
        $value = $this->get('vTribIBSMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribIBSMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribIBSMun' => $value]);
    }

    public function getVTribIBSUF(): ?string
    {
        $value = $this->get('vTribIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribIBSUF' => $value]);
    }
}
