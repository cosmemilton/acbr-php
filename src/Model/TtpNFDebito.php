<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TtpNFDebito: string
{
    case tdNenhum = 'tdNenhum';
    case tdTransferenciaCreditoCooperativa = 'tdTransferenciaCreditoCooperativa';
    case tdAnulacao = 'tdAnulacao';
    case tdDebitosNaoProcessadas = 'tdDebitosNaoProcessadas';
    case tdMultaJuros = 'tdMultaJuros';
    case tdTransferenciaCreditoSucessao = 'tdTransferenciaCreditoSucessao';
    case tdPagamentoAntecipado = 'tdPagamentoAntecipado';
    case tdPerdaEmEstoque = 'tdPerdaEmEstoque';
    case tdDesenquadramentodoSN = 'tdDesenquadramentodoSN';
}
