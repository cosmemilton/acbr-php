<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TTotal extends \Acbr\Document
{
    public const TYPE = 'TTotal';

    public function getIBSCBSTot(): ?TIBSCBSTot
    {
        $value = $this->get('IBSCBSTot');
        return $value === null ? null : new TIBSCBSTot($value, $this->contract);
    }

    /** @param TIBSCBSTot|array|\stdClass $value Validated without scalar coercion. */
    public function withIBSCBSTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IBSCBSTot' => $value]);
    }

    public function getICMSTot(): ?TICMSTot
    {
        $value = $this->get('ICMSTot');
        return $value === null ? null : new TICMSTot($value, $this->contract);
    }

    /** @param TICMSTot|array|\stdClass $value Validated without scalar coercion. */
    public function withICMSTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ICMSTot' => $value]);
    }

    public function getISSQNtot(): ?TISSQNtot
    {
        $value = $this->get('ISSQNtot');
        return $value === null ? null : new TISSQNtot($value, $this->contract);
    }

    /** @param TISSQNtot|array|\stdClass $value Validated without scalar coercion. */
    public function withISSQNtot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISSQNtot' => $value]);
    }

    public function getISTot(): ?TISTot
    {
        $value = $this->get('ISTot');
        return $value === null ? null : new TISTot($value, $this->contract);
    }

    /** @param TISTot|array|\stdClass $value Validated without scalar coercion. */
    public function withISTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['ISTot' => $value]);
    }

    public function getRetTrib(): ?TretTrib
    {
        $value = $this->get('retTrib');
        return $value === null ? null : new TretTrib($value, $this->contract);
    }

    /** @param TretTrib|array|\stdClass $value Validated without scalar coercion. */
    public function withRetTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['retTrib' => $value]);
    }

    public function getVNFTot(): ?string
    {
        $value = $this->get('vNFTot');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVNFTot(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vNFTot' => $value]);
    }
}
