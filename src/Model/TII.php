<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TII extends \Acbr\Document
{
    public const TYPE = 'TII';

    public function getVBc(): ?string
    {
        $value = $this->get('vBc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBc' => $value]);
    }

    public function getVDespAdu(): ?string
    {
        $value = $this->get('vDespAdu');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDespAdu(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDespAdu' => $value]);
    }

    public function getVII(): ?string
    {
        $value = $this->get('vII');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVII(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vII' => $value]);
    }

    public function getVIOF(): ?string
    {
        $value = $this->get('vIOF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIOF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIOF' => $value]);
    }
}
