<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TDeducCollection extends ModelCollection
{
    public const TYPE = 'TDeducCollection';

    public function get(int $index): TDeducCollectionItem
    {
        return parent::get($index);
    }
}
