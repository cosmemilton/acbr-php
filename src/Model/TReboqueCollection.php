<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TReboqueCollection extends ModelCollection
{
    public const TYPE = 'TReboqueCollection';

    public function get(int $index): TReboqueCollectionItem
    {
        return parent::get($index);
    }
}
