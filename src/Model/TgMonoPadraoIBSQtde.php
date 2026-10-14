<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoPadraoIBSQtde extends \Acbr\Document
{
    public const TYPE = 'TgMonoPadraoIBSQtde';

    public function getAdRemIBS(): ?string
    {
        $value = $this->get('adRemIBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withAdRemIBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['adRemIBS' => $value]);
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

    public function getVIBSMono(): ?string
    {
        $value = $this->get('vIBSMono');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMono' => $value]);
    }
}
