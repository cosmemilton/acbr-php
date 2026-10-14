<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnDeterminacaoBaseIcmsST: string
{
    case dbisPrecoTabelado = 'dbisPrecoTabelado';
    case dbisListaNegativa = 'dbisListaNegativa';
    case dbisListaPositiva = 'dbisListaPositiva';
    case dbisListaNeutra = 'dbisListaNeutra';
    case dbisMargemValorAgregado = 'dbisMargemValorAgregado';
    case dbisPauta = 'dbisPauta';
    case dbisValordaOperacao = 'dbisValordaOperacao';
}
