<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TautXMLCollection extends ModelCollection
{
    public const TYPE = 'TautXMLCollection';

    public function get(int $index): TautXMLCollectionItem
    {
        return parent::get($index);
    }
}
