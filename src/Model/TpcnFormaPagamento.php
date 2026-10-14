<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TpcnFormaPagamento: string
{
    case fpDinheiro = 'fpDinheiro';
    case fpCheque = 'fpCheque';
    case fpCartaoCredito = 'fpCartaoCredito';
    case fpCartaoDebito = 'fpCartaoDebito';
    case fpCreditoLoja = 'fpCreditoLoja';
    case fpValeAlimentacao = 'fpValeAlimentacao';
    case fpValeRefeicao = 'fpValeRefeicao';
    case fpValePresente = 'fpValePresente';
    case fpValeCombustivel = 'fpValeCombustivel';
    case fpDuplicataMercantil = 'fpDuplicataMercantil';
    case fpBoletoBancario = 'fpBoletoBancario';
    case fpDepositoBancario = 'fpDepositoBancario';
    case fpPagamentoInstantaneo = 'fpPagamentoInstantaneo';
    case fpTransfBancario = 'fpTransfBancario';
    case fpProgramaFidelidade = 'fpProgramaFidelidade';
    case fpSemPagamento = 'fpSemPagamento';
    case fpRegimeEspecial = 'fpRegimeEspecial';
    case fpOutro = 'fpOutro';
    case fpPagamentoInstantaneoEstatico = 'fpPagamentoInstantaneoEstatico';
    case fpCreditoEmLojaPorDevolucao = 'fpCreditoEmLojaPorDevolucao';
    case fpFalhaHardware = 'fpFalhaHardware';
    case fpPagamentoPosterior = 'fpPagamentoPosterior';
    case fpPagInstantaneoPIXAutomatico = 'fpPagInstantaneoPIXAutomatico';
    case fpTEFBookTransfer = 'fpTEFBookTransfer';
}
