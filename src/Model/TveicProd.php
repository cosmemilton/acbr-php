<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TveicProd extends \Acbr\Document
{
    public const TYPE = 'TveicProd';

    public function getAnoFab(): ?int
    {
        $value = $this->get('anoFab');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withAnoFab(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['anoFab' => $value]);
    }

    public function getAnoMod(): ?int
    {
        $value = $this->get('anoMod');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withAnoMod(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['anoMod' => $value]);
    }

    public function getCCor(): ?string
    {
        $value = $this->get('cCor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCCor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cCor' => $value]);
    }

    public function getCCorDENATRAN(): ?string
    {
        $value = $this->get('cCorDENATRAN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCCorDENATRAN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cCorDENATRAN' => $value]);
    }

    public function getChassi(): ?string
    {
        $value = $this->get('chassi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withChassi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['chassi' => $value]);
    }

    public function getCilin(): ?string
    {
        $value = $this->get('Cilin');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCilin(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['Cilin' => $value]);
    }

    public function getCMod(): ?string
    {
        $value = $this->get('cMod');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCMod(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMod' => $value]);
    }

    public function getCMT(): ?string
    {
        $value = $this->get('CMT');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCMT(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CMT' => $value]);
    }

    public function getCombDescricao(): ?string
    {
        $value = $this->get('CombDescricao');
        return $value === null ? null : $value;
    }

    public function getCondVeic(): ?TpcnCondicaoVeiculo
    {
        $value = $this->get('condVeic');
        return $value === null ? null : TpcnCondicaoVeiculo::from($value);
    }

    /** @param TpcnCondicaoVeiculo|string $value Validated without scalar coercion. */
    public function withCondVeic(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['condVeic' => $value]);
    }

    public function getDist(): ?string
    {
        $value = $this->get('dist');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDist(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dist' => $value]);
    }

    public function getEspVeic(): ?int
    {
        $value = $this->get('espVeic');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withEspVeic(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['espVeic' => $value]);
    }

    public function getLota(): ?int
    {
        $value = $this->get('lota');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withLota(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['lota' => $value]);
    }

    public function getNMotor(): ?string
    {
        $value = $this->get('nMotor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNMotor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nMotor' => $value]);
    }

    public function getNSerie(): ?string
    {
        $value = $this->get('nSerie');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNSerie(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nSerie' => $value]);
    }

    public function getPesoB(): ?string
    {
        $value = $this->get('pesoB');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPesoB(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pesoB' => $value]);
    }

    public function getPesoL(): ?string
    {
        $value = $this->get('pesoL');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPesoL(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pesoL' => $value]);
    }

    public function getPot(): ?string
    {
        $value = $this->get('pot');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pot' => $value]);
    }

    public function getTpComb(): ?string
    {
        $value = $this->get('tpComb');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withTpComb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpComb' => $value]);
    }

    public function getTpOP(): ?TpcnTipoOperacao
    {
        $value = $this->get('tpOP');
        return $value === null ? null : TpcnTipoOperacao::from($value);
    }

    /** @param TpcnTipoOperacao|string $value Validated without scalar coercion. */
    public function withTpOP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpOP' => $value]);
    }

    public function getTpPint(): ?string
    {
        $value = $this->get('tpPint');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withTpPint(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpPint' => $value]);
    }

    public function getTpRest(): ?int
    {
        $value = $this->get('tpRest');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withTpRest(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpRest' => $value]);
    }

    public function getTpVeic(): ?int
    {
        $value = $this->get('tpVeic');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withTpVeic(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpVeic' => $value]);
    }

    public function getVIN(): ?string
    {
        $value = $this->get('VIN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['VIN' => $value]);
    }

    public function getXCor(): ?string
    {
        $value = $this->get('xCor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withXCor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['xCor' => $value]);
    }
}
