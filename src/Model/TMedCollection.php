<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TMedCollection extends ModelCollection
{
    public const TYPE = 'TMedCollection';

    public function get(int $index): TMedCollectionItem
    {
        return parent::get($index);
    }
}
