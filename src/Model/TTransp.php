<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TTransp extends \Acbr\Document
{
    public const TYPE = 'TTransp';

    public function getBalsa(): ?string
    {
        $value = $this->get('balsa');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withBalsa(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['balsa' => $value]);
    }

    public function getModFrete(): ?TpcnModalidadeFrete
    {
        $value = $this->get('modFrete');
        return $value === null ? null : TpcnModalidadeFrete::from($value);
    }

    /** @param TpcnModalidadeFrete|string $value Validated without scalar coercion. */
    public function withModFrete(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['modFrete' => $value]);
    }

    public function getReboque(): ?TReboqueCollection
    {
        $value = $this->get('Reboque');
        return $value === null ? null : new TReboqueCollection($value, $this->contract);
    }

    /** @param TReboqueCollection|array $value Validated without scalar coercion. */
    public function withReboque(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Reboque' => $value]);
    }

    public function getRetTransp(): ?TretTransp
    {
        $value = $this->get('retTransp');
        return $value === null ? null : new TretTransp($value, $this->contract);
    }

    /** @param TretTransp|array|\stdClass $value Validated without scalar coercion. */
    public function withRetTransp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['retTransp' => $value]);
    }

    public function getTransporta(): ?TTransporta
    {
        $value = $this->get('Transporta');
        return $value === null ? null : new TTransporta($value, $this->contract);
    }

    /** @param TTransporta|array|\stdClass $value Validated without scalar coercion. */
    public function withTransporta(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Transporta' => $value]);
    }

    public function getVagao(): ?string
    {
        $value = $this->get('vagao');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVagao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vagao' => $value]);
    }

    public function getVeicTransp(): ?TveicTransp
    {
        $value = $this->get('veicTransp');
        return $value === null ? null : new TveicTransp($value, $this->contract);
    }

    /** @param TveicTransp|array|\stdClass $value Validated without scalar coercion. */
    public function withVeicTransp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['veicTransp' => $value]);
    }

    public function getVol(): ?TVolCollection
    {
        $value = $this->get('Vol');
        return $value === null ? null : new TVolCollection($value, $this->contract);
    }

    /** @param TVolCollection|array $value Validated without scalar coercion. */
    public function withVol(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Vol' => $value]);
    }
}
