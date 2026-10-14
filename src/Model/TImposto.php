<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TImposto extends \Acbr\Document
{
    public const TYPE = 'TImposto';

    public function getCOFINS(): ?TCOFINS
    {
        $value = $this->get('COFINS');
        return $value === null ? null : new TCOFINS($value, $this->contract);
    }

    /** @param TCOFINS|array|\stdClass $value Validated without scalar coercion. */
    public function withCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['COFINS' => $value]);
    }

    public function getCOFINSST(): ?TCOFINSST
    {
        $value = $this->get('COFINSST');
        return $value === null ? null : new TCOFINSST($value, $this->contract);
    }

    /** @param TCOFINSST|array|\stdClass $value Validated without scalar coercion. */
    public function withCOFINSST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['COFINSST' => $value]);
    }

    public function getIBSCBS(): ?TIBSCBS
    {
        $value = $this->get('IBSCBS');
        return $value === null ? null : new TIBSCBS($value, $this->contract);
    }

    /** @param TIBSCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withIBSCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IBSCBS' => $value]);
    }

    public function getICMS(): ?TICMS
    {
        $value = $this->get('ICMS');
        return $value === null ? null : new TICMS($value, $this->contract);
    }

    /** @param TICMS|array|\stdClass $value Validated without scalar coercion. */
    public function withICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMS' => $value]);
    }

    public function getICMSUFDest(): ?TICMSUFDest
    {
        $value = $this->get('ICMSUFDest');
        return $value === null ? null : new TICMSUFDest($value, $this->contract);
    }

    /** @param TICMSUFDest|array|\stdClass $value Validated without scalar coercion. */
    public function withICMSUFDest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMSUFDest' => $value]);
    }

    public function getII(): ?TII
    {
        $value = $this->get('II');
        return $value === null ? null : new TII($value, $this->contract);
    }

    /** @param TII|array|\stdClass $value Validated without scalar coercion. */
    public function withII(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['II' => $value]);
    }

    public function getIPI(): ?TIPI
    {
        $value = $this->get('IPI');
        return $value === null ? null : new TIPI($value, $this->contract);
    }

    /** @param TIPI|array|\stdClass $value Validated without scalar coercion. */
    public function withIPI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IPI' => $value]);
    }

    public function getISel(): ?TgIS
    {
        $value = $this->get('ISel');
        return $value === null ? null : new TgIS($value, $this->contract);
    }

    /** @param TgIS|array|\stdClass $value Validated without scalar coercion. */
    public function withISel(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISel' => $value]);
    }

    public function getISSQN(): ?TISSQN
    {
        $value = $this->get('ISSQN');
        return $value === null ? null : new TISSQN($value, $this->contract);
    }

    /** @param TISSQN|array|\stdClass $value Validated without scalar coercion. */
    public function withISSQN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISSQN' => $value]);
    }

    public function getPIS(): ?TPIS
    {
        $value = $this->get('PIS');
        return $value === null ? null : new TPIS($value, $this->contract);
    }

    /** @param TPIS|array|\stdClass $value Validated without scalar coercion. */
    public function withPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['PIS' => $value]);
    }

    public function getPISST(): ?TPISST
    {
        $value = $this->get('PISST');
        return $value === null ? null : new TPISST($value, $this->contract);
    }

    /** @param TPISST|array|\stdClass $value Validated without scalar coercion. */
    public function withPISST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['PISST' => $value]);
    }

    public function getVTotTrib(): ?string
    {
        $value = $this->get('vTotTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTotTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTotTrib' => $value]);
    }
}
