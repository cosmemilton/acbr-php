<?php
declare(strict_types=1);
namespace Acbr;
final readonly class Configuration
{
    public function __construct(#[\SensitiveParameter] public string $cnpj, public string $uf, public int $modelo = 55, public int $ambiente = 2)
    {
        if (!preg_match('/^\d{14}$/D', $cnpj) || !in_array($uf, explode(' ', 'AC AL AP AM BA CE DF ES GO MA MT MS MG PA PB PR PE PI RJ RN RS RO RR SC SP SE TO'), true) || !in_array($modelo, [55, 65], true) || !in_array($ambiente, [1, 2], true)) { throw new AcbrException('CONFIGURACAO_INVALIDA', 'CNPJ, UF, modelo ou ambiente inválido.'); }
    }
    public function toWire(): array { return ['cnpj'=>$this->cnpj, 'uf'=>$this->uf, 'modelo'=>$this->modelo, 'ambiente'=>$this->ambiente]; }
    public function __debugInfo(): array { return ['uf'=>$this->uf, 'modelo'=>$this->modelo, 'ambiente'=>$this->ambiente]; }
}
