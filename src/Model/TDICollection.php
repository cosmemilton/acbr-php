<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TDICollection extends ModelCollection
{
    public const TYPE = 'TDICollection';

    public function get(int $index): TDICollectionItem
    {
        return parent::get($index);
    }
}
