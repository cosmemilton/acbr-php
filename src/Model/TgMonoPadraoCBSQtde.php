<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoPadraoCBSQtde extends \Acbr\Document
{
    public const TYPE = 'TgMonoPadraoCBSQtde';

    public function getAdRemCBS(): ?string
    {
        $value = $this->get('adRemCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemCBS' => $value]);
    }

    public function getQBCMono(): ?string
    {
        $value = $this->get('qBCMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQBCMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qBCMono' => $value]);
    }

    public function getVCBSMono(): ?string
    {
        $value = $this->get('vCBSMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSMono' => $value]);
    }
}
