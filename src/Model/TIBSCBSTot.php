<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TIBSCBSTot extends \Acbr\Document
{
    public const TYPE = 'TIBSCBSTot';

    public function getGCBS(): ?TgCBS
    {
        $value = $this->get('gCBS');
        return $value === null ? null : new TgCBS($value, $this->contract);
    }

    /** @param TgCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCBS' => $value]);
    }

    public function getGEstornoCred(): ?TgEstornoCred
    {
        $value = $this->get('gEstornoCred');
        return $value === null ? null : new TgEstornoCred($value, $this->contract);
    }

    /** @param TgEstornoCred|array|\stdClass $value Validated without scalar coercion. */
    public function withGEstornoCred(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gEstornoCred' => $value]);
    }

    public function getGIBS(): ?TgIBS
    {
        $value = $this->get('gIBS');
        return $value === null ? null : new TgIBS($value, $this->contract);
    }

    /** @param TgIBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBS' => $value]);
    }

    public function getGMono(): ?TgMono
    {
        $value = $this->get('gMono');
        return $value === null ? null : new TgMono($value, $this->contract);
    }

    /** @param TgMono|array|\stdClass $value Validated without scalar coercion. */
    public function withGMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gMono' => $value]);
    }

    public function getVBCIBSCBS(): ?string
    {
        $value = $this->get('vBCIBSCBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCIBSCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCIBSCBS' => $value]);
    }
}
