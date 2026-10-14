<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TRastroCollection extends ModelCollection
{
    public const TYPE = 'TRastroCollection';

    public function get(int $index): TRastroCollectionItem
    {
        return parent::get($index);
    }
}
