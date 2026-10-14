<?php
declare(strict_types=1);
namespace Acbr;
final class AcbrException extends \RuntimeException implements \JsonSerializable
{
    public function __construct(public readonly string $codigo, string $message, public readonly bool $resultadoDesconhecido = false, public readonly bool $limpezaConfirmada = true) { parent::__construct($message); }
    public function jsonSerialize(): array { return ['codigo'=>$this->codigo, 'mensagem'=>$this->getMessage(), 'resultadoDesconhecido'=>$this->resultadoDesconhecido, 'limpezaConfirmada'=>$this->limpezaConfirmada]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
