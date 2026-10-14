<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnTipoOperacao: string
{
    case toVendaConcessionaria = 'toVendaConcessionaria';
    case toFaturamentoDireto = 'toFaturamentoDireto';
    case toVendaDireta = 'toVendaDireta';
    case toOutros = 'toOutros';
}
