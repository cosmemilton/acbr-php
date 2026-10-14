<?php

declare(strict_types=1);

namespace Acbr\Model;

/** A structural failure, containing contract paths but never submitted values. */
final class ValidationException extends \InvalidArgumentException implements \JsonSerializable
{
    private const MESSAGES = [
        'JSON_INVALIDO' => 'JSON inválido, codificação UTF-8 ou profundidade não suportada.',
        'MODELO_DESCONHECIDO' => 'Modelo desconhecido.',
        'ENUM_INVALIDO' => 'Valor de enum inválido.',
        'ARRAY_ESPERADO' => 'Esperada uma lista.',
        'OBJETO_ESPERADO' => 'Esperado um objeto.',
        'CAMPO_DESCONHECIDO' => 'Propriedade desconhecida; confira os campos deste grupo.',
        'CAMPO_SOMENTE_LEITURA' => 'Propriedade somente leitura.',
        'INTEIRO_INVALIDO' => 'Esperado inteiro dentro do intervalo do campo.',
        'BOOLEANO_ESPERADO' => 'Esperado booleano.',
        'DECIMAL_INVALIDO' => 'Esperado decimal em string, com ponto e escala permitida.',
        'DATA_HORA_INVALIDA' => 'Esperada data/hora civil ISO, sem Z ou offset.',
        'DATA_INVALIDA' => 'Data ou horário civil inválido.',
        'TEXTO_ESPERADO' => 'Esperado texto em string.',
        'PROFUNDIDADE_EXCEDIDA' => 'Documento excede a profundidade permitida.',
    ];

    public function __construct(public readonly string $codigo, public readonly ?string $campo = null)
    {
        $message = self::MESSAGES[$codigo] ?? self::MESSAGES['MODELO_DESCONHECIDO'];
        parent::__construct(($campo === null ? '' : $campo . ': ') . $message);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return array_filter([
            'codigo' => $this->codigo,
            'etapa' => 'documento',
            'campo' => $this->campo,
            'mensagem' => $this->getMessage(),
            'statusEnvio' => 'nao_enviado',
        ], static fn (mixed $value): bool => $value !== null);
    }
}
