<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TdefensivoCollection extends ModelCollection
{
    public const TYPE = 'TdefensivoCollection';

    public function get(int $index): TdefensivoCollectionItem
    {
        return parent::get($index);
    }
}
