<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TForDiaCollection extends ModelCollection
{
    public const TYPE = 'TForDiaCollection';

    public function get(int $index): TForDiaCollectionItem
    {
        return parent::get($index);
    }
}
