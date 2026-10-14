<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBS extends \Acbr\Document
{
    public const TYPE = 'TgIBS';

    public function getGIBSMunTot(): ?TgIBSMunTot
    {
        $value = $this->get('gIBSMunTot');
        return $value === null ? null : new TgIBSMunTot($value, $this->contract);
    }

    /** @param TgIBSMunTot|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSMunTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSMunTot' => $value]);
    }

    public function getGIBSUFTot(): ?TgIBSUFTot
    {
        $value = $this->get('gIBSUFTot');
        return $value === null ? null : new TgIBSUFTot($value, $this->contract);
    }

    /** @param TgIBSUFTot|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSUFTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSUFTot' => $value]);
    }

    public function getVCredPres(): ?string
    {
        $value = $this->get('vCredPres');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPres(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPres' => $value]);
    }

    public function getVCredPresCondSus(): ?string
    {
        $value = $this->get('vCredPresCondSus');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPresCondSus(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPresCondSus' => $value]);
    }

    public function getVIBS(): ?string
    {
        $value = $this->get('vIBS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBS' => $value]);
    }
}
