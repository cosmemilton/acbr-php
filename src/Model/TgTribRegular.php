<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgTribRegular extends \Acbr\Document
{
    public const TYPE = 'TgTribRegular';

    public function getCClassTribReg(): ?string
    {
        $value = $this->get('cClassTribReg');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCClassTribReg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cClassTribReg' => $value]);
    }

    public function getCSTReg(): ?TCSTIBSCBS
    {
        $value = $this->get('CSTReg');
        return $value === null ? null : TCSTIBSCBS::from($value);
    }

    /** @param TCSTIBSCBS|string $value Validated without scalar coercion. */
    public function withCSTReg(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CSTReg' => $value]);
    }

    public function getPAliqEfetRegCBS(): ?string
    {
        $value = $this->get('pAliqEfetRegCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqEfetRegCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqEfetRegCBS' => $value]);
    }

    public function getPAliqEfetRegIBSMun(): ?string
    {
        $value = $this->get('pAliqEfetRegIBSMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqEfetRegIBSMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqEfetRegIBSMun' => $value]);
    }

    public function getPAliqEfetRegIBSUF(): ?string
    {
        $value = $this->get('pAliqEfetRegIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPAliqEfetRegIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pAliqEfetRegIBSUF' => $value]);
    }

    public function getVTribRegCBS(): ?string
    {
        $value = $this->get('vTribRegCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribRegCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribRegCBS' => $value]);
    }

    public function getVTribRegIBSMun(): ?string
    {
        $value = $this->get('vTribRegIBSMun');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribRegIBSMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribRegIBSMun' => $value]);
    }

    public function getVTribRegIBSUF(): ?string
    {
        $value = $this->get('vTribRegIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTribRegIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTribRegIBSUF' => $value]);
    }
}
