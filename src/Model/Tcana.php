<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class Tcana extends \Acbr\Document
{
    public const TYPE = 'Tcana';

    public function getDeduc(): ?TDeducCollection
    {
        $value = $this->get('deduc');
        return $value === null ? null : new TDeducCollection($value, $this->contract);
    }

    /** @param TDeducCollection|array $value Validated without scalar coercion. */
    public function withDeduc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['deduc' => $value]);
    }

    public function getFordia(): ?TForDiaCollection
    {
        $value = $this->get('fordia');
        return $value === null ? null : new TForDiaCollection($value, $this->contract);
    }

    /** @param TForDiaCollection|array $value Validated without scalar coercion. */
    public function withFordia(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['fordia' => $value]);
    }

    public function getQTotAnt(): ?string
    {
        $value = $this->get('qTotAnt');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTotAnt(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTotAnt' => $value]);
    }

    public function getQTotGer(): ?string
    {
        $value = $this->get('qTotGer');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTotGer(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTotGer' => $value]);
    }

    public function getQTotMes(): ?string
    {
        $value = $this->get('qTotMes');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withQTotMes(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['qTotMes' => $value]);
    }

    public function getRef(): ?string
    {
        $value = $this->get('ref');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withRef(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ref' => $value]);
    }

    public function getSafra(): ?string
    {
        $value = $this->get('safra');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withSafra(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['safra' => $value]);
    }

    public function getVFor(): ?string
    {
        $value = $this->get('vFor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVFor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vFor' => $value]);
    }

    public function getVLiqFor(): ?string
    {
        $value = $this->get('vLiqFor');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVLiqFor(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vLiqFor' => $value]);
    }

    public function getVTotDed(): ?string
    {
        $value = $this->get('vTotDed');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTotDed(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTotDed' => $value]);
    }
}
