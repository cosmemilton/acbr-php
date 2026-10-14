<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TDetCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TDetCollectionItem';

    public function getDFeReferenciado(): ?TDFeReferenciado
    {
        $value = $this->get('DFeReferenciado');
        return $value === null ? null : new TDFeReferenciado($value, $this->contract);
    }

    /** @param TDFeReferenciado|array|\stdClass $value Validated without scalar coercion. */
    public function withDFeReferenciado(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['DFeReferenciado' => $value]);
    }

    public function getImposto(): ?TImposto
    {
        $value = $this->get('Imposto');
        return $value === null ? null : new TImposto($value, $this->contract);
    }

    /** @param TImposto|array|\stdClass $value Validated without scalar coercion. */
    public function withImposto(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Imposto' => $value]);
    }

    public function getInfAdProd(): ?string
    {
        $value = $this->get('infAdProd');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withInfAdProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['infAdProd' => $value]);
    }

    public function getObsCont(): ?TobsItem
    {
        $value = $this->get('obsCont');
        return $value === null ? null : new TobsItem($value, $this->contract);
    }

    /** @param TobsItem|array|\stdClass $value Validated without scalar coercion. */
    public function withObsCont(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['obsCont' => $value]);
    }

    public function getObsFisco(): ?TobsItem
    {
        $value = $this->get('obsFisco');
        return $value === null ? null : new TobsItem($value, $this->contract);
    }

    /** @param TobsItem|array|\stdClass $value Validated without scalar coercion. */
    public function withObsFisco(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['obsFisco' => $value]);
    }

    public function getPDevol(): ?string
    {
        $value = $this->get('pDevol');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPDevol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pDevol' => $value]);
    }

    public function getProd(): ?TProd
    {
        $value = $this->get('Prod');
        return $value === null ? null : new TProd($value, $this->contract);
    }

    /** @param TProd|array|\stdClass $value Validated without scalar coercion. */
    public function withProd(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Prod' => $value]);
    }

    public function getVIPIDevol(): ?string
    {
        $value = $this->get('vIPIDevol');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIPIDevol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIPIDevol' => $value]);
    }

    public function getVItem(): ?string
    {
        $value = $this->get('vItem');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVItem(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vItem' => $value]);
    }
}
