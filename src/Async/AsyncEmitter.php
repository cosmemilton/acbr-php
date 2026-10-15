<?php
declare(strict_types=1);
namespace Acbr\Async;

use Acbr\Document;
use Acbr\Emitter;
use Acbr\FiscalResult;

/** Optional asynchronous facade over the same validated synchronous emitter. */
final class AsyncEmitter implements \JsonSerializable
{
    private readonly OperationQueue $queue;

    public function __construct(#[\SensitiveParameter] private readonly Emitter $emitter, int $capacity = 16, int $timeoutMs = 120000, int $maxBytes = 8388608)
    {
        $this->queue = new OperationQueue($capacity, $timeoutMs, $maxBytes);
    }

    public function gerarXml(#[\SensitiveParameter] Document|array $document): Future
    {
        $data = $document instanceof Document ? $document : new Document($document);
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->gerarXml($data, $cancelled), false);
    }
    public function validar(#[\SensitiveParameter] string $xml): Future
    {
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->validar($xml, $cancelled), false);
    }
    public function assinar(#[\SensitiveParameter] string $xml): Future
    {
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->assinar($xml, $cancelled), false);
    }
    public function transmitir(#[\SensitiveParameter] string $xml): Future
    {
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->transmitir($xml, $cancelled), true);
    }
    public function consultar(#[\SensitiveParameter] array $data): Future
    {
        $data = $this->snapshot($data);
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->consultar($data, $cancelled), false);
    }
    public function cancelar(#[\SensitiveParameter] array $data): Future
    {
        $data = $this->snapshot($data);
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->cancelar($data, $cancelled), true);
    }
    public function cartaCorrecao(#[\SensitiveParameter] array $data): Future
    {
        $data = $this->snapshot($data);
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->cartaCorrecao($data, $cancelled), true);
    }
    public function inutilizar(#[\SensitiveParameter] array $data): Future
    {
        $data = $this->snapshot($data);
        $emitter = $this->emitter;
        return $this->queue->submit(static fn(#[\SensitiveParameter] callable $cancelled): FiscalResult => $emitter->inutilizar($data, $cancelled), true);
    }
    /** These command payloads are flat: copy values to detach PHP references. */
    private function snapshot(#[\SensitiveParameter] array $data): array
    {
        $copy = [];
        foreach ($data as $key => $value) { $copy[$key] = $value; }
        return $copy;
    }
    public function tick(int $waitMs = 0): void { $this->queue->tick($waitMs); }
    public function drain(): void { $this->queue->drain(); }
    public function fechar(): void
    {
        try { $this->queue->close(); }
        finally { $this->emitter->fechar(); }
    }
    public function jsonSerialize(): array { return $this->queue->jsonSerialize(); }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
}
