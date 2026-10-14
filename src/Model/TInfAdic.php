<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TInfAdic extends \Acbr\Document
{
    public const TYPE = 'TInfAdic';

    public function getInfAdFisco(): ?string
    {
        $value = $this->get('infAdFisco');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withInfAdFisco(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infAdFisco' => $value]);
    }

    public function getInfCpl(): ?string
    {
        $value = $this->get('infCpl');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withInfCpl(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infCpl' => $value]);
    }

    public function getObsCont(): ?TobsContCollection
    {
        $value = $this->get('obsCont');
        return $value === null ? null : new TobsContCollection($value, $this->contract);
    }

    /** @param TobsContCollection|array $value Validated without scalar coercion. */
    public function withObsCont(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['obsCont' => $value]);
    }

    public function getObsFisco(): ?TobsFiscoCollection
    {
        $value = $this->get('obsFisco');
        return $value === null ? null : new TobsFiscoCollection($value, $this->contract);
    }

    /** @param TobsFiscoCollection|array $value Validated without scalar coercion. */
    public function withObsFisco(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['obsFisco' => $value]);
    }

    public function getProcRef(): ?TprocRefCollection
    {
        $value = $this->get('procRef');
        return $value === null ? null : new TprocRefCollection($value, $this->contract);
    }

    /** @param TprocRefCollection|array $value Validated without scalar coercion. */
    public function withProcRef(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['procRef' => $value]);
    }
}
