<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TprocRefCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TprocRefCollectionItem';

    public function getIndProc(): ?TpcnIndicadorProcesso
    {
        $value = $this->get('indProc');
        return $value === null ? null : TpcnIndicadorProcesso::from($value);
    }

    /** @param TpcnIndicadorProcesso|string $value Validated without scalar coercion. */
    public function withIndProc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['indProc' => $value]);
    }

    public function getNProc(): ?string
    {
        $value = $this->get('nProc');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withNProc(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['nProc' => $value]);
    }

    public function getTpAto(): ?TtpAto
    {
        $value = $this->get('tpAto');
        return $value === null ? null : TtpAto::from($value);
    }

    /** @param TtpAto|string $value Validated without scalar coercion. */
    public function withTpAto(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['tpAto' => $value]);
    }
}
