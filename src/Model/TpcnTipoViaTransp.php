<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnTipoViaTransp: string
{
    case tvMaritima = 'tvMaritima';
    case tvFluvial = 'tvFluvial';
    case tvLacustre = 'tvLacustre';
    case tvAerea = 'tvAerea';
    case tvPostal = 'tvPostal';
    case tvFerroviaria = 'tvFerroviaria';
    case tvRodoviaria = 'tvRodoviaria';
    case tvConduto = 'tvConduto';
    case tvMeiosProprios = 'tvMeiosProprios';
    case tvEntradaSaidaFicta = 'tvEntradaSaidaFicta';
    case tvCourier = 'tvCourier';
    case tvEmMaos = 'tvEmMaos';
    case tvPorReboque = 'tvPorReboque';
}
