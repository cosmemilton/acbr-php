<?php
declare(strict_types=1);
namespace Acbr;

/** Injection boundary for deterministic offline tests or explicitly selected engines. */
interface Engine
{
    public function execute(#[\SensitiveParameter] array $request, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult;
}
