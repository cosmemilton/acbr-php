<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TACBrTipoEmissao: string
{
    case teNormal = 'teNormal';
    case teContingencia = 'teContingencia';
    case teSCAN = 'teSCAN';
    case teDPEC = 'teDPEC';
    case teFSDA = 'teFSDA';
    case teSVCAN = 'teSVCAN';
    case teSVCRS = 'teSVCRS';
    case teSVCSP = 'teSVCSP';
    case teOffLine = 'teOffLine';
}
