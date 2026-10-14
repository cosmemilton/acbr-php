<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TpagCollection extends ModelCollection
{
    public const TYPE = 'TpagCollection';

    public function get(int $index): TpagCollectionItem
    {
        return parent::get($index);
    }
}
