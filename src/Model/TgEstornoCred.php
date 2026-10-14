<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgEstornoCred extends \Acbr\Document
{
    public const TYPE = 'TgEstornoCred';

    public function getVCBSEstCred(): ?string
    {
        $value = $this->get('vCBSEstCred');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSEstCred(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSEstCred' => $value]);
    }

    public function getVIBSEstCred(): ?string
    {
        $value = $this->get('vIBSEstCred');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSEstCred(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSEstCred' => $value]);
    }
}
