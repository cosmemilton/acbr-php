<?php
declare(strict_types=1);
namespace Acbr;
final readonly class FiscalResult implements \JsonSerializable
{
    public function __construct(#[\SensitiveParameter] private array $data, private ?string $command = null) {}
    public function sucesso(): bool { return $this->data['sucesso']; }
    public function estado(): string { return $this->data['estado']; }
    public function comando(): ?string { return $this->command; }
    public function xml(): ?string
    {
        if (!is_string($this->data['xmlBase64'] ?? null)) { return null; }
        $xml = base64_decode($this->data['xmlBase64'], true);
        return $xml === false ? null : $xml;
    }
    /** Explicit access may contain fiscal identifiers/XML. Never log indiscriminately. */
    public function data(): array { return $this->data; }
    /** Explicit private ACBr fields retained in the worker frame; no invented missing diagnostic. */
    public function retornoOriginal(): array
    {
        $original = array_intersect_key($this->data, array_flip(['cStat','xMotivo','protocolo','chave','recibo','xmlBase64']));
        $xml = $this->xml();
        if ($xml !== null) { $original['xmlDecodificado'] = $xml; }
        return ['origem'=>'frame_nativo'] + ($this->command === 'recuperarXml' ? ['retornoWebServiceOriginalDisponivel'=>false] : []) + $original;
    }
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
        if ($this->command === 'recuperarXml' || $state === 'XML_RECUPERADO') {
            $xml = $this->xml();
            $validXml = is_string($xml) && $xml !== '' && strlen($xml) <= 4194304 && preg_match('//u', $xml) === 1
                && !str_contains($xml, "\0") && base64_encode($xml) === ($this->data['xmlBase64'] ?? null);
            if ($this->command === 'recuperarXml' && $state === 'XML_RECUPERADO' && $success === true && $code === 100 && $protocol && $key && $sending === false && $validXml) { return 'autorizado'; }
            // A recovery lookup never starts a fiscal submission. Failure does not prove authorization.
            return $state === 'ERRO' && $sending === false ? 'nao_enviado' : 'resultado_incerto';
        }
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
        if ($this->command === 'recuperarXml' || ($this->data['estado'] ?? null) === 'XML_RECUPERADO') {
            $safe = [];
            foreach (['sucesso','envioIniciado'] as $field) { if (is_bool($this->data[$field] ?? null)) { $safe[$field] = $this->data[$field]; } }
            if (in_array($this->data['estado'] ?? null, ['XML_RECUPERADO','CONSULTADO','ERRO','REJEITADO','DENEGADO','INDETERMINADO','PENDENTE'], true)) { $safe['estado'] = $this->data['estado']; }
            $code = $this->data['codigo'] ?? null;
            if (is_string($code) && in_array($code, self::RECOVERY_CODES, true)) { $safe['codigo'] = $code; }
            if (is_int($this->data['cStat'] ?? null) && $this->data['cStat'] >= 1 && $this->data['cStat'] <= 9999) { $safe['cStat'] = $this->data['cStat']; }
            return $safe + ['statusEnvio'=>$this->statusEnvio()];
        }
        return array_intersect_key($this->data, array_flip(['sucesso','estado','codigo','cStat','envioIniciado'])) + ['statusEnvio'=>$this->statusEnvio()];
    }
    private const RECOVERY_CODES = ['RECUPERACAO_NAO_COMPROVADA','XML_INVALIDO','XML_NAO_ASSINADO','XML_ALTERADO','XML_SCHEMA_INVALIDO','SCHEMAS_AUSENTES','IDENTIDADE_DIVERGENTE','ASSINATURA_INVALIDA','CERTIFICADO_AUSENTE','CERTIFICADO_INVALIDO','CERTIFICADO_DIVERGENTE','TLS_SERVIDOR_INVALIDO','OPERACAO_FISCAL_FALHOU','RESULTADO_INDETERMINADO','RETORNO_INVALIDO','CONTRATO_INCOMPATIVEL','LIMITE_EXCEDIDO','REQUISICAO_INVALIDA','DEPENDENCIA_AUSENTE','SERVICOS_AUSENTES','FALHA_MOTOR'];
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
