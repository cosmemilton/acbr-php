<?php
declare(strict_types=1);
namespace Acbr;

/** One synchronous request per operation. Never retries fiscal operations automatically. */
final class Emitter
{
    private bool $closed = false;
    private readonly Contract $contract;
    private readonly Engine $engine;
    public function __construct(
        #[\SensitiveParameter] private readonly Configuration $configuration,
        ?string $runtimeDirectory = null,
        int $timeoutMs = 120000,
        int $maxBytes = 8388608,
        ?Engine $engine = null,
    ) {
        $this->contract = Contract::bundled();
        $native = new NativeEngine($runtimeDirectory, $timeoutMs, $maxBytes);
        $this->engine = $engine ?? $native;
    }
    public function gerarXml(#[\SensitiveParameter] Document|array $document, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $value = $document instanceof Document ? $document->jsonSerialize() : $document;
        $value = $this->contract->normalize($value);
        return $this->request('gerarXml', ['documento'=>$value], $cancelled);
    }
    public function validar(#[\SensitiveParameter] string $xml, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        return $this->request('validar', $this->xmlData($xml), $cancelled);
    }
    public function assinar(#[\SensitiveParameter] string $xml, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        return $this->request('assinar', $this->xmlData($xml), $cancelled);
    }
    public function transmitir(#[\SensitiveParameter] string $xml, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        return $this->request('transmitir', $this->xmlData($xml), $cancelled);
    }
    public function consultar(#[\SensitiveParameter] array $data, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $this->keys($data, ['chave','recibo']);
        $this->digits($data['chave'] ?? null, 44, 'CHAVE_INVALIDA');
        if (array_key_exists('recibo', $data)) { $this->digits($data['recibo'], 15, 'RECIBO_INVALIDO'); }
        return $this->request('consultar', $data, $cancelled);
    }
    public function cancelar(#[\SensitiveParameter] array $data, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $this->keys($data, ['chave','protocolo','justificativa']);
        $this->digits($data['chave'] ?? null, 44, 'CHAVE_INVALIDA');
        $this->digits($data['protocolo'] ?? null, 15, 'PROTOCOLO_INVALIDO');
        $this->text($data['justificativa'] ?? null, 15, 255, 'JUSTIFICATIVA_INVALIDA');
        return $this->request('cancelar', $data, $cancelled);
    }
    public function cartaCorrecao(#[\SensitiveParameter] array $data, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $this->keys($data, ['chave','correcao','sequenciaEvento']);
        if ($this->configuration->modelo !== 55) { throw new AcbrException('OPERACAO_NAO_SUPORTADA', 'Carta de correção exige NF-e modelo 55.'); }
        $this->digits($data['chave'] ?? null, 44, 'CHAVE_INVALIDA');
        $this->text($data['correcao'] ?? null, 15, 1000, 'CORRECAO_INVALIDA');
        $sequence = $data['sequenciaEvento'] ?? 1;
        if (!is_int($sequence) || $sequence < 1 || $sequence > 20) { throw new AcbrException('CORRECAO_INVALIDA', 'Sequência da carta de correção deve ser de 1 a 20.'); }
        $data['sequenciaEvento'] = $sequence;
        return $this->request('cartaCorrecao', $data, $cancelled);
    }
    public function inutilizar(#[\SensitiveParameter] array $data, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        $this->keys($data, ['ano','serie','numeroInicial','numeroFinal','justificativa']);
        $this->text($data['justificativa'] ?? null, 15, 255, 'JUSTIFICATIVA_INVALIDA');
        foreach (['ano','serie','numeroInicial','numeroFinal'] as $key) {
            if (!isset($data[$key]) || !is_int($data[$key])) { throw new AcbrException('INUTILIZACAO_INVALIDA', 'Faixa de inutilização inválida.'); }
        }
        if ($data['ano'] < 0 || $data['ano'] > 99 || $data['serie'] < 0 || $data['serie'] > 999 || $data['numeroInicial'] < 1 || $data['numeroFinal'] < $data['numeroInicial'] || $data['numeroFinal'] > 999999999) { throw new AcbrException('INUTILIZACAO_INVALIDA', 'Faixa de inutilização inválida.'); }
        return $this->request('inutilizar', ['inutilizacao'=>array_intersect_key($data, array_flip(['ano','serie','numeroInicial','numeroFinal'])), 'justificativa'=>$data['justificativa']], $cancelled);
    }
    public function fechar(): void { $this->closed = true; }
    public function __debugInfo(): array { return ['fechado'=>$this->closed, 'configuracao'=>$this->configuration->__debugInfo()]; }

    private function xmlData(#[\SensitiveParameter] string $xml): array
    {
        if (trim($xml) === '' || strlen($xml) > 2097152 || preg_match('//u', $xml) !== 1) { throw new AcbrException('XML_INVALIDO', 'XML vazio, UTF-8 inválido ou acima de 2 MiB.'); }
        return ['xmlBase64'=>base64_encode($xml)];
    }
    private function keys(#[\SensitiveParameter] array $data, array $allowed): void
    {
        if (array_diff(array_keys($data), $allowed) !== []) { throw new AcbrException('OPERACAO_INVALIDA', 'Campo desconhecido nos parâmetros da operação.'); }
    }
    private function digits(#[\SensitiveParameter] mixed $value, int $length, string $code): void
    {
        if (!is_string($value) || preg_match('/\A[0-9]{'.$length.'}\z/D', $value) !== 1) { throw new AcbrException($code, 'Identificador fiscal inválido.'); }
    }
    private function text(#[\SensitiveParameter] mixed $value, int $min, int $max, string $code): void
    {
        // Native Pascal counts UTF-8 bytes; enforce its exact limits, with valid UTF-8.
        if (!is_string($value) || strlen($value) < $min || strlen($value) > $max || preg_match('//u', $value) !== 1) { throw new AcbrException($code, 'Texto fiscal inválido ou fora do limite do motor.'); }
    }
    private function request(string $command, #[\SensitiveParameter] array $data, #[\SensitiveParameter] ?callable $cancelled): FiscalResult
    {
        if ($this->closed) { throw new AcbrException('EMISSOR_FECHADO', 'Emissor encerrado.'); }
        if ($cancelled && $cancelled()) { throw new AcbrException('OPERACAO_CANCELADA', 'Operação cancelada antes da execução.'); }
        if (in_array($command, ['assinar','transmitir','consultar','cancelar','cartaCorrecao','inutilizar'], true) && !$this->configuration->hasCertificate()) { throw new AcbrException('CERTIFICADO_AUSENTE', 'Esta operação exige um certificado A1.'); }
        $metadata = $this->contract->metadata();
        $request = ['versao'=>1, 'id'=>bin2hex(random_bytes(16)), 'sourceRevision'=>$metadata['sourceRevision'], 'contractHash'=>$metadata['contractHash'], 'comando'=>$command, 'config'=>$this->configuration->toWire()] + $data;
        try { return new FiscalResult($this->engine->execute($request, $cancelled)->data(), $command); }
        catch (AcbrException $error) { if (!$error->limpezaConfirmada) { $this->closed = true; } throw $error; }
    }
}
