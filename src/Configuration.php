<?php
declare(strict_types=1);
namespace Acbr;
final readonly class Configuration implements \JsonSerializable
{
    public function __construct(
        #[\SensitiveParameter] public string $cnpj,
        public string $uf,
        public int $modelo = 55,
        public int $ambiente = 2,
        #[\SensitiveParameter] private ?CertificateA1 $certificado = null,
        #[\SensitiveParameter] private ?Csc $csc = null,
    )
    {
        if (!preg_match('/^\d{14}$/D', $cnpj) || !in_array($uf, explode(' ', 'AC AL AP AM BA CE DF ES GO MA MT MS MG PA PB PR PE PI RJ RN RS RO RR SC SP SE TO'), true) || !in_array($modelo, [55, 65], true) || !in_array($ambiente, [1, 2], true)) { throw new AcbrException('CONFIGURACAO_INVALIDA', 'CNPJ, UF, modelo ou ambiente inválido.'); }
    }
    public function hasCertificate(): bool { return $this->certificado !== null; }
    public function hasCsc(): bool { return $this->csc !== null; }
    /** @internal Explicit secret-bearing payload for the worker's stdin only. Never log. */
    public function toWire(): array
    {
        $config = ['cnpj'=>$this->cnpj, 'uf'=>$this->uf, 'modelo'=>$this->modelo, 'ambiente'=>$this->ambiente];
        if ($this->certificado !== null) { $config['certificado'] = $this->certificado->toWire(); }
        if ($this->csc !== null) { $config['csc'] = $this->csc->toWire(); }
        return $config;
    }
    public function jsonSerialize(): array { return ['uf'=>$this->uf, 'modelo'=>$this->modelo, 'ambiente'=>$this->ambiente, 'certificadoConfigurado'=>$this->hasCertificate(), 'cscConfigurado'=>$this->hasCsc()]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
    public function __serialize(): array { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Não serialize configurações fiscais.'); }
    public function __unserialize(#[\SensitiveParameter] array $data): void { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Reconstrua a configuração pelo construtor.'); }
}
