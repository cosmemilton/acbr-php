<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TobsFiscoCollection extends ModelCollection
{
    public const TYPE = 'TobsFiscoCollection';

    public function get(int $index): TobsFiscoCollectionItem
    {
        return parent::get($index);
    }
}
