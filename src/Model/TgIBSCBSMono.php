<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSCBSMono extends \Acbr\Document
{
    public const TYPE = 'TgIBSCBSMono';

    public function getGCBSMonoAdRem(): ?TgCBSMonoAdRem
    {
        $value = $this->get('gCBSMonoAdRem');
        return $value === null ? null : new TgCBSMonoAdRem($value, $this->contract);
    }

    /** @param TgCBSMonoAdRem|array|\stdClass $value Validated without scalar coercion. */
    public function withGCBSMonoAdRem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCBSMonoAdRem' => $value]);
    }

    public function getGCBSMonoAdValorem(): ?TgCBSMonoAdValorem
    {
        $value = $this->get('gCBSMonoAdValorem');
        return $value === null ? null : new TgCBSMonoAdValorem($value, $this->contract);
    }

    /** @param TgCBSMonoAdValorem|array|\stdClass $value Validated without scalar coercion. */
    public function withGCBSMonoAdValorem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCBSMonoAdValorem' => $value]);
    }

    public function getGIBSMonoAdRem(): ?TgIBSMonoAdRem
    {
        $value = $this->get('gIBSMonoAdRem');
        return $value === null ? null : new TgIBSMonoAdRem($value, $this->contract);
    }

    /** @param TgIBSMonoAdRem|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSMonoAdRem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSMonoAdRem' => $value]);
    }

    public function getGIBSMonoAdValorem(): ?TgIBSMonoAdValorem
    {
        $value = $this->get('gIBSMonoAdValorem');
        return $value === null ? null : new TgIBSMonoAdValorem($value, $this->contract);
    }

    /** @param TgIBSMonoAdValorem|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSMonoAdValorem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSMonoAdValorem' => $value]);
    }

    public function getVTotCBSMonoItem(): ?string
    {
        $value = $this->get('vTotCBSMonoItem');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTotCBSMonoItem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTotCBSMonoItem' => $value]);
    }

    public function getVTotIBSMonoItem(): ?string
    {
        $value = $this->get('vTotIBSMonoItem');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTotIBSMonoItem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTotIBSMonoItem' => $value]);
    }
}
