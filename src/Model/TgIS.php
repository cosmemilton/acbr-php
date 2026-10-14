<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIS extends \Acbr\Document
{
    public const TYPE = 'TgIS';

    public function getAdRemIS(): ?string
    {
        $value = $this->get('adRemIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemIS' => $value]);
    }

    public function getCClassTribIS(): ?string
    {
        $value = $this->get('cClassTribIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCClassTribIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cClassTribIS' => $value]);
    }

    public function getCSTIS(): ?string
    {
        $value = $this->get('CSTIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCSTIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CSTIS' => $value]);
    }

    public function getPIS(): ?string
    {
        $value = $this->get('pIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pIS' => $value]);
    }

    public function getQTrib(): ?string
    {
        $value = $this->get('qTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTrib' => $value]);
    }

    public function getUTrib(): ?string
    {
        $value = $this->get('uTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['uTrib' => $value]);
    }

    public function getVBCIS(): ?string
    {
        $value = $this->get('vBCIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCIS' => $value]);
    }

    public function getVIS(): ?string
    {
        $value = $this->get('vIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIS' => $value]);
    }
}
