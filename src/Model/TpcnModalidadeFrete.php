<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnModalidadeFrete: string
{
    case mfContaEmitente = 'mfContaEmitente';
    case mfContaDestinatario = 'mfContaDestinatario';
    case mfContaTerceiros = 'mfContaTerceiros';
    case mfProprioRemetente = 'mfProprioRemetente';
    case mfProprioDestinatario = 'mfProprioDestinatario';
    case mfSemFrete = 'mfSemFrete';
}
