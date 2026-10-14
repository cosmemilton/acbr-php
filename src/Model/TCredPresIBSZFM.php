<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCredPresIBSZFM extends \Acbr\Document
{
    public const TYPE = 'TCredPresIBSZFM';

    public function getCompetApur(): ?string
    {
        $value = $this->get('competApur');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCompetApur(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['competApur' => $value]);
    }

    public function getTpCredPresIBSZFM(): ?TTpCredPresIBSZFM
    {
        $value = $this->get('tpCredPresIBSZFM');
        return $value === null ? null : TTpCredPresIBSZFM::from($value);
    }

    /** @param TTpCredPresIBSZFM|string $value Validated without scalar coercion. */
    public function withTpCredPresIBSZFM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpCredPresIBSZFM' => $value]);
    }

    public function getVCredPresIBSZFM(): ?string
    {
        $value = $this->get('vCredPresIBSZFM');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPresIBSZFM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPresIBSZFM' => $value]);
    }
}
