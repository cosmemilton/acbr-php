<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCompra extends \Acbr\Document
{
    public const TYPE = 'TCompra';

    public function getXCont(): ?string
    {
        $value = $this->get('xCont');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXCont(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xCont' => $value]);
    }

    public function getXNEmp(): ?string
    {
        $value = $this->get('xNEmp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXNEmp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xNEmp' => $value]);
    }

    public function getXPed(): ?string
    {
        $value = $this->get('xPed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXPed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xPed' => $value]);
    }
}
