<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TIBSCBS extends \Acbr\Document
{
    public const TYPE = 'TIBSCBS';

    public function getCClassTrib(): ?string
    {
        $value = $this->get('cClassTrib');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCClassTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cClassTrib' => $value]);
    }

    public function getCST(): ?TCSTIBSCBS
    {
        $value = $this->get('CST');
        return $value === null ? null : TCSTIBSCBS::from($value);
    }

    /** @param TCSTIBSCBS|string $value Validated without scalar coercion. */
    public function withCST(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['CST' => $value]);
    }

    public function getGAjusteCompet(): ?TgAjusteCompet
    {
        $value = $this->get('gAjusteCompet');
        return $value === null ? null : new TgAjusteCompet($value, $this->contract);
    }

    /** @param TgAjusteCompet|array|\stdClass $value Validated without scalar coercion. */
    public function withGAjusteCompet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gAjusteCompet' => $value]);
    }

    public function getGCredPresIBSZFM(): ?TCredPresIBSZFM
    {
        $value = $this->get('gCredPresIBSZFM');
        return $value === null ? null : new TCredPresIBSZFM($value, $this->contract);
    }

    /** @param TCredPresIBSZFM|array|\stdClass $value Validated without scalar coercion. */
    public function withGCredPresIBSZFM(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCredPresIBSZFM' => $value]);
    }

    public function getGCredPresOper(): ?TgCredPresOper
    {
        $value = $this->get('gCredPresOper');
        return $value === null ? null : new TgCredPresOper($value, $this->contract);
    }

    /** @param TgCredPresOper|array|\stdClass $value Validated without scalar coercion. */
    public function withGCredPresOper(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gCredPresOper' => $value]);
    }

    public function getGEstornoCred(): ?TgEstornoCred
    {
        $value = $this->get('gEstornoCred');
        return $value === null ? null : new TgEstornoCred($value, $this->contract);
    }

    /** @param TgEstornoCred|array|\stdClass $value Validated without scalar coercion. */
    public function withGEstornoCred(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gEstornoCred' => $value]);
    }

    public function getGIBSCBS(): ?TgIBSCBS
    {
        $value = $this->get('gIBSCBS');
        return $value === null ? null : new TgIBSCBS($value, $this->contract);
    }

    /** @param TgIBSCBS|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSCBS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSCBS' => $value]);
    }

    public function getGIBSCBSMono(): ?TgIBSCBSMono
    {
        $value = $this->get('gIBSCBSMono');
        return $value === null ? null : new TgIBSCBSMono($value, $this->contract);
    }

    /** @param TgIBSCBSMono|array|\stdClass $value Validated without scalar coercion. */
    public function withGIBSCBSMono(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gIBSCBSMono' => $value]);
    }

    public function getGTransfCred(): ?TgTransfCred
    {
        $value = $this->get('gTransfCred');
        return $value === null ? null : new TgTransfCred($value, $this->contract);
    }

    /** @param TgTransfCred|array|\stdClass $value Validated without scalar coercion. */
    public function withGTransfCred(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['gTransfCred' => $value]);
    }

    public function getIndDoacao(): ?TIndicadorEx
    {
        $value = $this->get('indDoacao');
        return $value === null ? null : TIndicadorEx::from($value);
    }

    /** @param TIndicadorEx|string $value Validated without scalar coercion. */
    public function withIndDoacao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indDoacao' => $value]);
    }
}
