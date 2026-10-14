<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TCredPresumidoCollectionItem extends \Acbr\Document
{
    public const TYPE = 'TCredPresumidoCollectionItem';

    public function getCCredPresumido(): ?string
    {
        $value = $this->get('cCredPresumido');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withCCredPresumido(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['cCredPresumido' => $value]);
    }

    public function getPCredPresumido(): ?string
    {
        $value = $this->get('pCredPresumido');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withPCredPresumido(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['pCredPresumido' => $value]);
    }

    public function getVCredPresumido(): ?string
    {
        $value = $this->get('vCredPresumido');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCredPresumido(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCredPresumido' => $value]);
    }
}
