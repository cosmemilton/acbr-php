<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TorigCombCollection extends ModelCollection
{
    public const TYPE = 'TorigCombCollection';

    public function get(int $index): TorigCombCollectionItem
    {
        return parent::get($index);
    }
}
