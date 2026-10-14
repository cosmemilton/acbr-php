<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetIBS extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetIBS';

    public function getVIBSMonoRet(): ?string
    {
        $value = $this->get('vIBSMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVIBSMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vIBSMonoRet' => $value]);
    }
}
