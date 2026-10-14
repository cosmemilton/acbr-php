<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TgMonoRetCBS extends \Acbr\Document
{
    public const TYPE = 'TgMonoRetCBS';

    public function getVCBSMonoRet(): ?string
    {
        $value = $this->get('vCBSMonoRet');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withVCBSMonoRet(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['vCBSMonoRet' => $value]);
    }
}
