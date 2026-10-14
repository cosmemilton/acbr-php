<?php
declare(strict_types=1);
namespace Acbr;

/** Initial offline MVP. Network/signing methods are deliberately not exposed. */
final class Emitter
{
    private bool $closed = false;
    private readonly Contract $contract;
    private readonly ProcessTransport $transport;
    public function __construct(#[\SensitiveParameter] private readonly Configuration $configuration, private readonly ?string $runtimeDirectory = null, int $timeoutMs = 120000, int $maxBytes = 8388608)
    {
        $this->contract = Contract::bundled();
        $this->transport = new ProcessTransport($timeoutMs, $maxBytes);
    }
    public function gerarXml(#[\SensitiveParameter] Document|array $document, ?callable $cancelled = null): FiscalResult
    {
        $value = $document instanceof Document ? $document->jsonSerialize() : $document;
        $value = $this->contract->normalize($value);
        return $this->request('gerarXml', ['documento'=>$value], $cancelled);
    }
    public function validar(#[\SensitiveParameter] string $xml, ?callable $cancelled = null): FiscalResult
    {
        if (trim($xml) === '' || strlen($xml) > 2097152) { throw new AcbrException('XML_INVALIDO', 'XML vazio ou acima de 2 MiB.'); }
        return $this->request('validar', ['xmlBase64'=>base64_encode($xml)], $cancelled);
    }
    public function fechar(): void { $this->closed = true; }
    private function request(string $command, #[\SensitiveParameter] array $data, ?callable $cancelled): FiscalResult
    {
        if ($this->closed) { throw new AcbrException('EMISSOR_FECHADO', 'Emissor encerrado.'); }
        $runtime = NativeRuntime::resolve($this->runtimeDirectory);
        $metadata = $this->contract->metadata();
        $request = ['versao'=>1, 'id'=>bin2hex(random_bytes(16)), 'sourceRevision'=>$metadata['sourceRevision'], 'contractHash'=>$metadata['contractHash'], 'comando'=>$command, 'config'=>$this->configuration->toWire()] + $data;
        try { return $this->transport->execute($runtime->executable, $runtime->directory, $runtime->environment(), $request, $cancelled); }
        catch (AcbrException $error) { if (!$error->limpezaConfirmada) { $this->closed = true; } throw $error; }
    }
}
