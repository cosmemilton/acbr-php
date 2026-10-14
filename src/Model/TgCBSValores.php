<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgCBSValores extends \Acbr\Document
{
    public const TYPE = 'TgCBSValores';

    public function getGALCZFMCBS(): ?TgALCZFMCBS
    {
        $value = $this->get('gALCZFMCBS');
        return $value === null ? null : new TgALCZFMCBS($value, $this->contract);
    }

    /** @param TgALCZFMCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGALCZFMCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gALCZFMCBS' => $value]);
    }

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

    public function getPCBS(): ?string
    {
        $value = $this->get('pCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pCBS' => $value]);
    }

    public function getVCBS(): ?string
    {
        $value = $this->get('vCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBS' => $value]);
    }
}
