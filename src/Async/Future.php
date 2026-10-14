<?php
declare(strict_types=1);
namespace Acbr\Async;

use Acbr\AcbrException;
use Acbr\FiscalResult;

/** A single queued operation. Its owner must remain alive until settlement. */
final class Future implements \JsonSerializable
{
    private string $state = 'pending';
    private ?FiscalResult $value = null;
    private ?AcbrException $error = null;
    private readonly \WeakReference $owner;

    /** @internal Created by OperationQueue. */
    public function __construct(OperationQueue $owner, private readonly int $id)
    {
        $this->owner = \WeakReference::create($owner);
    }

    public function state(): string { $this->pump(); return $this->state; }
    public function isComplete(): bool { $this->pump(); return $this->settled(); }

    /** Requests cancellation; false means the operation was already settled. */
    public function cancel(): bool
    {
        $queue = $this->owner->get();
        return $queue instanceof OperationQueue && $queue->cancel($this->id);
    }

    /** Waits for this future while also progressing its owner's FIFO queue. */
    public function await(): FiscalResult
    {
        while (!$this->settled()) {
            $queue = $this->owner->get();
            if (!$queue instanceof OperationQueue) {
                throw new AcbrException('FILA_FECHADA', 'A fila assíncrona não está mais disponível.');
            }
            $queue->tick(20);
        }
        return $this->result();
    }

    /** Returns only a completed result; never implicitly waits. */
    public function result(): FiscalResult
    {
        $this->pump();
        if ($this->error !== null) { throw $this->error; }
        if ($this->value === null) { throw new AcbrException('RESULTADO_PENDENTE', 'A operação assíncrona ainda não terminou.'); }
        return $this->value;
    }

    /** @internal */
    public function markRunning(): void { if (!$this->settled()) { $this->state = 'running'; } }
    /** @internal */
    public function resolve(#[\SensitiveParameter] FiscalResult $result): void
    {
        if (!$this->settled()) { $this->value = $result; $this->state = 'fulfilled'; }
    }
    /** @internal */
    public function reject(#[\SensitiveParameter] AcbrException $error): void
    {
        if (!$this->settled()) { $this->error = $error; $this->state = 'rejected'; }
    }
    public function jsonSerialize(): array { return ['state'=>$this->state]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
    private function settled(): bool { return $this->state === 'fulfilled' || $this->state === 'rejected'; }
    private function pump(): void
    {
        if (!$this->settled()) {
            $queue = $this->owner->get();
            if ($queue instanceof OperationQueue) { $queue->tick(); }
        }
    }
}
