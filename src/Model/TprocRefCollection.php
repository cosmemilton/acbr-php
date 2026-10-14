<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TprocRefCollection extends ModelCollection
{
    public const TYPE = 'TprocRefCollection';

    public function get(int $index): TprocRefCollectionItem
    {
        return parent::get($index);
    }
}
