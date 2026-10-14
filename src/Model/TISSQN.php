<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TISSQN extends \Acbr\Document
{
    public const TYPE = 'TISSQN';

    public function getCListServ(): ?string
    {
        $value = $this->get('cListServ');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCListServ(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cListServ' => $value]);
    }

    public function getCMun(): ?int
    {
        $value = $this->get('cMun');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMun(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMun' => $value]);
    }

    public function getCMunFG(): ?int
    {
        $value = $this->get('cMunFG');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCMunFG(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cMunFG' => $value]);
    }

    public function getCPais(): ?int
    {
        $value = $this->get('cPais');
        return $value === null ? null : $value;
    }

    /** @param int $value Validated without scalar coercion. */
    public function withCPais(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cPais' => $value]);
    }

    public function getCServico(): ?string
    {
        $value = $this->get('cServico');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCServico(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cServico' => $value]);
    }

    public function getCSitTrib(): ?TpcnISSQNcSitTrib
    {
        $value = $this->get('cSitTrib');
        return $value === null ? null : TpcnISSQNcSitTrib::from($value);
    }

    /** @param TpcnISSQNcSitTrib|string $value Validated without scalar coercion. */
    public function withCSitTrib(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cSitTrib' => $value]);
    }

    public function getIndIncentivo(): ?TindIncentivo
    {
        $value = $this->get('indIncentivo');
        return $value === null ? null : TindIncentivo::from($value);
    }

    /** @param TindIncentivo|string $value Validated without scalar coercion. */
    public function withIndIncentivo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indIncentivo' => $value]);
    }

    public function getIndISS(): ?TpcnindISS
    {
        $value = $this->get('indISS');
        return $value === null ? null : TpcnindISS::from($value);
    }

    /** @param TpcnindISS|string $value Validated without scalar coercion. */
    public function withIndISS(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indISS' => $value]);
    }

    public function getIndISSRet(): ?TpcnindISSRet
    {
        $value = $this->get('indISSRet');
        return $value === null ? null : TpcnindISSRet::from($value);
    }

    /** @param TpcnindISSRet|string $value Validated without scalar coercion. */
    public function withIndISSRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indISSRet' => $value]);
    }

    public function getNProcesso(): ?string
    {
        $value = $this->get('nProcesso');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNProcesso(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nProcesso' => $value]);
    }

    public function getVAliq(): ?string
    {
        $value = $this->get('vAliq');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVAliq(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vAliq' => $value]);
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

    public function getVISSQN(): ?string
    {
        $value = $this->get('vISSQN');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVISSQN(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vISSQN' => $value]);
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
}
