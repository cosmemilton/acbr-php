<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSUFValores extends \Acbr\Document
{
    public const TYPE = 'TgIBSUFValores';

    public function getGDevTrib(): ?TgDevTrib
    {
        $value = $this->get('gDevTrib');
        return $value === null ? null : new TgDevTrib($value, $this->contract);
    }

    /** @param TgDevTrib|array|\stdClass $value Validated without scalar coercion. */
    public function withGDevTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gDevTrib' => $value]);
    }

    public function getGDif(): ?TgDif
    {
        $value = $this->get('gDif');
        return $value === null ? null : new TgDif($value, $this->contract);
    }

    /** @param TgDif|array|\stdClass $value Validated without scalar coercion. */
    public function withGDif(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gDif' => $value]);
    }

    public function getGRed(): ?TgRed
    {
        $value = $this->get('gRed');
        return $value === null ? null : new TgRed($value, $this->contract);
    }

    /** @param TgRed|array|\stdClass $value Validated without scalar coercion. */
    public function withGRed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gRed' => $value]);
    }

    public function getPIBSUF(): ?string
    {
        $value = $this->get('pIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pIBSUF' => $value]);
    }

    public function getVIBSUF(): ?string
    {
        $value = $this->get('vIBSUF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSUF' => $value]);
    }
}
