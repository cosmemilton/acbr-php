<?php
declare(strict_types=1);
namespace Acbr;
final class AcbrException extends \RuntimeException implements \JsonSerializable
{
    public function __construct(
        public readonly string $codigo,
        string $message,
        public readonly bool $resultadoDesconhecido = false,
        public readonly bool $limpezaConfirmada = true,
        #[\SensitiveParameter] private readonly ?string $xmlRecoveryBase64 = null,
    ) { parent::__construct($message); }
    /** Explicit recovery only. Never blindly retransmit after an uncertain fiscal outcome. */
    public function xmlRecuperacao(): ?string
    {
        return $this->xmlRecoveryBase64 === null ? null : (base64_decode($this->xmlRecoveryBase64, true) ?: null);
    }
    public function jsonSerialize(): array { return ['codigo'=>$this->codigo, 'mensagem'=>$this->getMessage(), 'resultadoDesconhecido'=>$this->resultadoDesconhecido, 'limpezaConfirmada'=>$this->limpezaConfirmada]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
