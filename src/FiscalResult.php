<?php
declare(strict_types=1);
namespace Acbr;
final readonly class FiscalResult implements \JsonSerializable
{
    public function __construct(#[\SensitiveParameter] private array $data) {}
    public function sucesso(): bool { return $this->data['sucesso']; }
    public function estado(): string { return $this->data['estado']; }
    public function xml(): ?string { return isset($this->data['xmlBase64']) ? base64_decode($this->data['xmlBase64'], true) : null; }
    /** Explicit access may contain fiscal identifiers/XML. Never log indiscriminately. */
    public function data(): array { return $this->data; }
    public function jsonSerialize(): array { return array_intersect_key($this->data, array_flip(['sucesso', 'estado', 'codigo', 'cStat', 'envioIniciado'])); }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
