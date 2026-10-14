<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgCompraGov extends \Acbr\Document
{
    public const TYPE = 'TgCompraGov';

    public function getPRedutor(): ?string
    {
        $value = $this->get('pRedutor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPRedutor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pRedutor' => $value]);
    }

    public function getRefDFeAnt(): ?TDFErefCollection
    {
        $value = $this->get('refDFeAnt');
        return $value === null ? null : new TDFErefCollection($value, $this->contract);
    }

    /** @param TDFErefCollection|array $value Validated without scalar coercion. */
    public function withRefDFeAnt(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['refDFeAnt' => $value]);
    }

    public function getTpEnteGov(): ?TtpEnteGov
    {
        $value = $this->get('tpEnteGov');
        return $value === null ? null : TtpEnteGov::from($value);
    }

    /** @param TtpEnteGov|string $value Validated without scalar coercion. */
    public function withTpEnteGov(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpEnteGov' => $value]);
    }

    public function getTpOperGov(): ?TtpOperGov
    {
        $value = $this->get('tpOperGov');
        return $value === null ? null : TtpOperGov::from($value);
    }

    /** @param TtpOperGov|string $value Validated without scalar coercion. */
    public function withTpOperGov(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpOperGov' => $value]);
    }
}
