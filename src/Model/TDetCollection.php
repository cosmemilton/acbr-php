<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TDetCollection extends ModelCollection
{
    public const TYPE = 'TDetCollection';

    public function get(int $index): TDetCollectionItem
    {
        return parent::get($index);
    }
}
