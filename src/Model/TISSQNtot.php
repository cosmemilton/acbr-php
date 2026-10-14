<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TISSQNtot extends \Acbr\Document
{
    public const TYPE = 'TISSQNtot';

    public function getCRegTrib(): ?TRegTribISSQN
    {
        $value = $this->get('cRegTrib');
        return $value === null ? null : TRegTribISSQN::from($value);
    }

    /** @param TRegTribISSQN|string $value Validated without scalar coercion. */
    public function withCRegTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cRegTrib' => $value]);
    }

    public function getDCompet(): ?string
    {
        $value = $this->get('dCompet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDCompet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['dCompet' => $value]);
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

    public function getVCOFINS(): ?string
    {
        $value = $this->get('vCOFINS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCOFINS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCOFINS' => $value]);
    }

    public function getVDeducao(): ?string
    {
        $value = $this->get('vDeducao');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDeducao(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDeducao' => $value]);
    }

    public function getVDescCond(): ?string
    {
        $value = $this->get('vDescCond');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDescCond(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDescCond' => $value]);
    }

    public function getVDescIncond(): ?string
    {
        $value = $this->get('vDescIncond');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVDescIncond(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vDescIncond' => $value]);
    }

    public function getVISS(): ?string
    {
        $value = $this->get('vISS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVISS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vISS' => $value]);
    }

    public function getVISSRet(): ?string
    {
        $value = $this->get('vISSRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVISSRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vISSRet' => $value]);
    }

    public function getVOutro(): ?string
    {
        $value = $this->get('vOutro');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVOutro(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vOutro' => $value]);
    }

    public function getVPIS(): ?string
    {
        $value = $this->get('vPIS');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVPIS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vPIS' => $value]);
    }

    public function getVServ(): ?string
    {
        $value = $this->get('vServ');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVServ(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vServ' => $value]);
    }
}
