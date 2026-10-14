<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TtpNFCredito: string
{
    case tcNenhum = 'tcNenhum';
    case tcMultaJuros = 'tcMultaJuros';
    case tcApropriacaoCreditoPresumido = 'tcApropriacaoCreditoPresumido';
    case tcRetorno = 'tcRetorno';
    case tcReducaoValores = 'tcReducaoValores';
    case tcTransferenciaCreditoSucessao = 'tcTransferenciaCreditoSucessao';
    case tcRetornoRecusaParcial = 'tcRetornoRecusaParcial';
}
