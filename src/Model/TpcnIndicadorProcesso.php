<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnIndicadorProcesso: string
{
    case ipSEFAZ = 'ipSEFAZ';
    case ipJusticaFederal = 'ipJusticaFederal';
    case ipJusticaEstadual = 'ipJusticaEstadual';
    case ipSecexRFB = 'ipSecexRFB';
    case ipCONFAZ = 'ipCONFAZ';
    case ipOutros = 'ipOutros';
}
