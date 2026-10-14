<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
enum TOrigemMercadoria: string
{
    case oeNacional = 'oeNacional';
    case oeEstrangeiraImportacaoDireta = 'oeEstrangeiraImportacaoDireta';
    case oeEstrangeiraAdquiridaBrasil = 'oeEstrangeiraAdquiridaBrasil';
    case oeNacionalConteudoImportacaoSuperior40 = 'oeNacionalConteudoImportacaoSuperior40';
    case oeNacionalProcessosBasicos = 'oeNacionalProcessosBasicos';
    case oeNacionalConteudoImportacaoInferiorIgual40 = 'oeNacionalConteudoImportacaoInferiorIgual40';
    case oeEstrangeiraImportacaoDiretaSemSimilar = 'oeEstrangeiraImportacaoDiretaSemSimilar';
    case oeEstrangeiraAdquiridaBrasilSemSimilar = 'oeEstrangeiraAdquiridaBrasilSemSimilar';
    case oeNacionalConteudoImportacaoSuperior70 = 'oeNacionalConteudoImportacaoSuperior70';
    case oeReservadoParaUsoFuturo = 'oeReservadoParaUsoFuturo';
    case oeVazio = 'oeVazio';
}
