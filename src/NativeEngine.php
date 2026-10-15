<?php
declare(strict_types=1);
namespace Acbr;
final class NativeEngine implements Engine
{
    private readonly ProcessTransport $transport;
    public function __construct(private readonly ?string $runtimeDirectory = null, int $timeoutMs = 120000, int $maxBytes = 8388608)
    {
        $this->transport = new ProcessTransport($timeoutMs, $maxBytes);
    }
    public function execute(#[\SensitiveParameter] array $request, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        if ($cancelled && $cancelled()) { throw new AcbrException('OPERACAO_CANCELADA', 'Operação cancelada antes da execução.'); }
        $runtime = NativeRuntime::resolve($this->runtimeDirectory);
        return $this->transport->execute($runtime->executable, $runtime->directory, $runtime->environment(), $request, $cancelled);
    }
}
