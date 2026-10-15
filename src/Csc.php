<?php
declare(strict_types=1);
namespace Acbr;

/** NFC-e token; contains a secret value and deliberately has no public properties. */
final readonly class Csc implements \JsonSerializable
{
    public function __construct(#[\SensitiveParameter] private string $id, #[\SensitiveParameter] private string $valor)
    {
        // NativeProtocol counts UTF-8 bytes, even though the Node facade counts UTF-16 units.
        if (preg_match('/^\d{1,6}$/D', $id) !== 1 || $valor === '' || strlen($valor) > 256 || preg_match('//u', $valor) !== 1) {
            throw new AcbrException('CSC_INVALIDO', 'Informe um identificador CSC de 1 a 6 dígitos e valor UTF-8 de 1 a 256 bytes.');
        }
    }
    /** @internal Explicit secret-bearing payload for the worker's stdin only. Never log. */
    public function toWire(): array { return ['id'=>$this->id, 'valor'=>$this->valor]; }
    public function jsonSerialize(): array { return ['configurado'=>true]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
    public function __serialize(): array { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Não serialize o CSC. Use somente o envio explícito ao motor.'); }
    public function __unserialize(#[\SensitiveParameter] array $data): void { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Reconstrua o CSC pelo construtor.'); }
}
