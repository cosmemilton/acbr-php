<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TdetExportCollection extends ModelCollection
{
    public const TYPE = 'TdetExportCollection';

    public function get(int $index): TdetExportCollectionItem
    {
        return parent::get($index);
    }
}
