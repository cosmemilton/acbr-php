<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnPresencaComprador: string
{
    case pcNao = 'pcNao';
    case pcPresencial = 'pcPresencial';
    case pcInternet = 'pcInternet';
    case pcTeleatendimento = 'pcTeleatendimento';
    case pcEntregaDomicilio = 'pcEntregaDomicilio';
    case pcPresencialForaEstabelecimento = 'pcPresencialForaEstabelecimento';
    case pcOutros = 'pcOutros';
}
