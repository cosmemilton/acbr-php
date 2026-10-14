<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TDFErefCollection extends ModelCollection
{
    public const TYPE = 'TDFErefCollection';

    public function get(int $index): TDFErefCollectionItem
    {
        return parent::get($index);
    }
}
