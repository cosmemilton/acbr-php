<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TComb extends \Acbr\Document
{
    public const TYPE = 'TComb';

    public function getCIDE(): ?TCIDE
    {
        $value = $this->get('CIDE');
        return $value === null ? null : new TCIDE($value, $this->contract);
    }

    /** @param TCIDE|array|\stdClass $value Validated without scalar coercion. */
    public function withCIDE(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CIDE' => $value]);
    }

    public function getCODIF(): ?string
    {
        $value = $this->get('CODIF');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCODIF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CODIF' => $value]);
    }

    public function getCProdANP(): ?int
    {
        $value = $this->get('cProdANP');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCProdANP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cProdANP' => $value]);
    }

    public function getDescANP(): ?string
    {
        $value = $this->get('descANP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDescANP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['descANP' => $value]);
    }

    public function getEncerrante(): ?Tencerrante
    {
        $value = $this->get('encerrante');
        return $value === null ? null : new Tencerrante($value, $this->contract);
    }

    /** @param Tencerrante|array|\stdClass $value Validated without scalar coercion. */
    public function withEncerrante(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['encerrante' => $value]);
    }

    public function getICMS(): ?TICMSComb
    {
        $value = $this->get('ICMS');
        return $value === null ? null : new TICMSComb($value, $this->contract);
    }

    /** @param TICMSComb|array|\stdClass $value Validated without scalar coercion. */
    public function withICMS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMS' => $value]);
    }

    public function getICMSCons(): ?TICMSCons
    {
        $value = $this->get('ICMSCons');
        return $value === null ? null : new TICMSCons($value, $this->contract);
    }

    /** @param TICMSCons|array|\stdClass $value Validated without scalar coercion. */
    public function withICMSCons(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMSCons' => $value]);
    }

    public function getICMSInter(): ?TICMSInter
    {
        $value = $this->get('ICMSInter');
        return $value === null ? null : new TICMSInter($value, $this->contract);
    }

    /** @param TICMSInter|array|\stdClass $value Validated without scalar coercion. */
    public function withICMSInter(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMSInter' => $value]);
    }

    public function getOrigComb(): ?TorigCombCollection
    {
        $value = $this->get('origComb');
        return $value === null ? null : new TorigCombCollection($value, $this->contract);
    }

    /** @param TorigCombCollection|array $value Validated without scalar coercion. */
    public function withOrigComb(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['origComb' => $value]);
    }

    public function getPBio(): ?string
    {
        $value = $this->get('pBio');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPBio(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pBio' => $value]);
    }

    public function getPGLP(): ?string
    {
        $value = $this->get('pGLP');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPGLP(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pGLP' => $value]);
    }

    public function getPGNi(): ?string
    {
        $value = $this->get('pGNi');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPGNi(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pGNi' => $value]);
    }

    public function getPGNn(): ?string
    {
        $value = $this->get('pGNn');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPGNn(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pGNn' => $value]);
    }

    public function getPMixGN(): ?string
    {
        $value = $this->get('pMixGN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPMixGN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pMixGN' => $value]);
    }

    public function getQTemp(): ?string
    {
        $value = $this->get('qTemp');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTemp(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTemp' => $value]);
    }

    public function getUFcons(): ?string
    {
        $value = $this->get('UFcons');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withUFcons(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['UFcons' => $value]);
    }

    public function getVPart(): ?string
    {
        $value = $this->get('vPart');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVPart(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vPart' => $value]);
    }
}
