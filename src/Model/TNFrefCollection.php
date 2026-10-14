<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TNFrefCollection extends ModelCollection
{
    public const TYPE = 'TNFrefCollection';

    public function get(int $index): TNFrefCollectionItem
    {
        return parent::get($index);
    }
}
