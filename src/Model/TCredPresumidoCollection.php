<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
/** @extends ModelCollection */
final class TCredPresumidoCollection extends ModelCollection
{
    public const TYPE = 'TCredPresumidoCollection';

    public function get(int $index): TCredPresumidoCollectionItem
    {
        return parent::get($index);
    }
}
