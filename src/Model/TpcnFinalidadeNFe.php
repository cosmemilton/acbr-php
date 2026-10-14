<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnFinalidadeNFe: string
{
    case fnNormal = 'fnNormal';
    case fnComplementar = 'fnComplementar';
    case fnAjuste = 'fnAjuste';
    case fnDevolucao = 'fnDevolucao';
    case fnCredito = 'fnCredito';
    case fnDebito = 'fnDebito';
}
