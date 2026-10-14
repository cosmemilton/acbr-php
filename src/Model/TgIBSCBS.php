<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgIBSCBS extends \Acbr\Document
{
    public const TYPE = 'TgIBSCBS';

    public function getGCBS(): ?TgCBSValores
    {
        $value = $this->get('gCBS');
        return $value === null ? null : new TgCBSValores($value, $this->contract);
    }

    /** @param TgCBSValores|array|\stdClass $value Validated without scalar coercion. */
    public function withGCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCBS' => $value]);
    }

    public function getGIBSMun(): ?TgIBSMunValores
    {
        $value = $this->get('gIBSMun');
        return $value === null ? null : new TgIBSMunValores($value, $this->contract);
    }

    /** @param TgIBSMunValores|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSMun' => $value]);
    }

    public function getGIBSUF(): ?TgIBSUFValores
    {
        $value = $this->get('gIBSUF');
        return $value === null ? null : new TgIBSUFValores($value, $this->contract);
    }

    /** @param TgIBSUFValores|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSUF(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSUF' => $value]);
    }

    public function getGTribCompraGov(): ?TgTribCompraGov
    {
        $value = $this->get('gTribCompraGov');
        return $value === null ? null : new TgTribCompraGov($value, $this->contract);
    }

    /** @param TgTribCompraGov|array|\stdClass $value Validated without scalar coercion. */
    public function withGTribCompraGov(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gTribCompraGov' => $value]);
    }

    public function getGTribRegular(): ?TgTribRegular
    {
        $value = $this->get('gTribRegular');
        return $value === null ? null : new TgTribRegular($value, $this->contract);
    }

    /** @param TgTribRegular|array|\stdClass $value Validated without scalar coercion. */
    public function withGTribRegular(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gTribRegular' => $value]);
    }

    public function getVBC(): ?string
    {
        $value = $this->get('vBC');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVBC(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vBC' => $value]);
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

    public function getVOperacIndiv(): ?string
    {
        $value = $this->get('vOperacIndiv');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVOperacIndiv(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vOperacIndiv' => $value]);
    }

    public function getVRedAjusteIndiv(): ?string
    {
        $value = $this->get('vRedAjusteIndiv');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRedAjusteIndiv(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRedAjusteIndiv' => $value]);
    }

    public function getVRedSocialIndiv(): ?string
    {
        $value = $this->get('vRedSocialIndiv');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVRedSocialIndiv(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vRedSocialIndiv' => $value]);
    }

    public function getVTornaIndiv(): ?string
    {
        $value = $this->get('vTornaIndiv');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVTornaIndiv(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vTornaIndiv' => $value]);
    }
}
