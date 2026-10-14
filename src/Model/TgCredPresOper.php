<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgCredPresOper extends \Acbr\Document
{
    public const TYPE = 'TgCredPresOper';

    public function getCCredPres(): ?TcCredPres
    {
        $value = $this->get('cCredPres');
        return $value === null ? null : TcCredPres::from($value);
    }

    /** @param TcCredPres|string $value Validated without scalar coercion. */
    public function withCCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cCredPres' => $value]);
    }

    public function getGCBSCredPres(): ?TgIBSCBSCredPres
    {
        $value = $this->get('gCBSCredPres');
        return $value === null ? null : new TgIBSCBSCredPres($value, $this->contract);
    }

    /** @param TgIBSCBSCredPres|array|\stdClass $value Validated without scalar coercion. */
    public function withGCBSCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCBSCredPres' => $value]);
    }

    public function getGIBSCredPres(): ?TgIBSCBSCredPres
    {
        $value = $this->get('gIBSCredPres');
        return $value === null ? null : new TgIBSCBSCredPres($value, $this->contract);
    }

    /** @param TgIBSCBSCredPres|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSCredPres' => $value]);
    }

    public function getVBCCredPres(): ?string
    {
        $value = $this->get('vBCCredPres');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBCCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBCCredPres' => $value]);
    }
}
