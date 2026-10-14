<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class Tagropecuario extends \Acbr\Document
{
    public const TYPE = 'Tagropecuario';

    public function getDefensivo(): ?TdefensivoCollection
    {
        $value = $this->get('defensivo');
        return $value === null ? null : new TdefensivoCollection($value, $this->contract);
    }

    /** @param TdefensivoCollection|array $value Validated without scalar coercion. */
    public function withDefensivo(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['defensivo' => $value]);
    }

    public function getGuiaTransito(): ?TguiaTransito
    {
        $value = $this->get('guiaTransito');
        return $value === null ? null : new TguiaTransito($value, $this->contract);
    }

    /** @param TguiaTransito|array|\stdClass $value Validated without scalar coercion. */
    public function withGuiaTransito(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['guiaTransito' => $value]);
    }
}
