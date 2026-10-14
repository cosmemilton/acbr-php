<?php
declare(strict_types=1);
namespace Acbr;
final readonly class FiscalResult implements \JsonSerializable
{
    public function __construct(#[\SensitiveParameter] private array $data, private ?string $command = null) {}
    public function sucesso(): bool { return $this->data['sucesso']; }
    public function estado(): string { return $this->data['estado']; }
    public function comando(): ?string { return $this->command; }
    public function xml(): ?string { return isset($this->data['xmlBase64']) ? base64_decode($this->data['xmlBase64'], true) : null; }
    /** Explicit access may contain fiscal identifiers/XML. Never log indiscriminately. */
    public function data(): array { return $this->data; }
    public function resultadoDesconhecido(): bool { return $this->statusEnvio() === 'resultado_incerto'; }

    /** Conservative, operation-aware classification aligned with upstream diagnostics. */
    public function statusEnvio(): string
    {
        $state = $this->data['estado'] ?? null;
        $success = $this->data['sucesso'] ?? null;
        $sending = $this->data['envioIniciado'] ?? null;
        if (array_key_exists('envioIniciado', $this->data) && !is_bool($this->data['envioIniciado'])) { return 'resultado_incerto'; }
        $code = $this->data['cStat'] ?? null;
        $protocol = is_string($this->data['protocolo'] ?? null) && preg_match('/\A[0-9]{15}\z/D', $this->data['protocolo']) === 1;
        $key = is_string($this->data['chave'] ?? null) && preg_match('/\A[0-9]{44}\z/D', $this->data['chave']) === 1;
        $local = ['gerarXml'=>'XML_GERADO','validar'=>'VALIDADO','assinar'=>'ASSINADO'];
        if (isset($local[$this->command ?? '']) && $state === $local[$this->command] && $success === true && $sending !== true) { return 'nao_enviado'; }
        $authorization = in_array($this->command, ['transmitir','consultar'], true) && $state === 'AUTORIZADO' && in_array($code, [100,150], true) && $protocol && $key && ($this->command === 'consultar' || $sending !== false);
        $event = ($this->command === 'cancelar' && $state === 'CANCELADO' && in_array($code, [135,136], true) && $key)
            || ($this->command === 'cartaCorrecao' && $state === 'EVENTO_REGISTRADO' && $code === 135 && $key)
            || ($this->command === 'inutilizar' && $state === 'INUTILIZADO' && $code === 102);
        if ($success === true && ($authorization || ($event && $protocol && $sending !== false))) { return 'autorizado'; }
        $nonFinal = [100,150,110,301,302,101,151,155,103,104,105,108,109,204,539,573,135,136,102,217];
        if ($success === false && ($this->command === 'consultar' || $sending !== false)
            && in_array($this->command, ['transmitir','consultar','cancelar','cartaCorrecao','inutilizar'], true)
            && is_int($code) && $code > 0 && $code <= 9999
            && (($state === 'DENEGADO' && in_array($code,[110,301,302],true)) || ($state === 'REJEITADO' && !in_array($code,$nonFinal,true)))) { return 'rejeitado'; }
        if ($state === 'ERRO' && $sending === false) { return 'nao_enviado'; }
        return 'resultado_incerto';
    }
    public function jsonSerialize(): array
    {
        return array_intersect_key($this->data, array_flip(['sucesso','estado','codigo','cStat','envioIniciado'])) + ['statusEnvio'=>$this->statusEnvio()];
    }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
