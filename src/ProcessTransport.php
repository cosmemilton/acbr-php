<?php
declare(strict_types=1);
namespace Acbr;

/** One owned child per request; never a shell, never a fiscal retry. */
final class ProcessTransport
{
    private const STATES = ['DENEGADO','XML_GERADO','VALIDADO','ASSINADO','AUTORIZADO','XML_RECUPERADO','REJEITADO','PENDENTE','INDETERMINADO','CONSULTADO','CANCELADO','EVENTO_REGISTRADO','INUTILIZADO','ERRO'];
    public function __construct(private readonly int $timeoutMs = 120000, private readonly int $maxBytes = 8388608)
    {
        if ($timeoutMs < 1 || $timeoutMs > 120000 || $maxBytes < 256 || $maxBytes > 8388608) {
            throw new AcbrException('CONFIGURACAO_INVALIDA', 'Prazo ou limite de bytes inválido.');
        }
    }

    /** @internal Caller must verify executable and assets before invoking. */
    public function execute(string $executable, string $directory, #[\SensitiveParameter] array $environment, #[\SensitiveParameter] array $request, #[\SensitiveParameter] ?callable $cancelled = null): FiscalResult
    {
        if (!function_exists('proc_open')) { throw new AcbrException('PROC_OPEN_INDISPONIVEL', 'Habilite proc_open para executar o motor.'); }
        if ($cancelled && $cancelled()) { throw new AcbrException('OPERACAO_CANCELADA', 'Operação cancelada antes da execução.'); }
        try { $bytes = json_encode($request, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"; }
        catch (\JsonException) { throw new AcbrException('REQUISICAO_INVALIDA', 'Dados não serializáveis.'); }
        if (strlen($bytes) > $this->maxBytes) { throw new AcbrException('REQUISICAO_EXCESSIVA', 'Requisição excede o limite de bytes.'); }
        $process = @proc_open([$executable, '--parent-pid', (string)getmypid()], [0=>['pipe','r'], 1=>['pipe','w'], 2=>['pipe','w']], $pipes, $directory, $environment, ['bypass_shell'=>true]);
        if (!is_resource($process)) { throw new AcbrException('FALHA_INICIAR_MOTOR', 'Não foi possível iniciar o motor.'); }
        $deadline = hrtime(true) + $this->timeoutMs * 1000000;
        $offset = 0; $total = 0; $buffer = ''; $result = null; $sending = false; $exit = null;
        $uncertain = in_array($request['comando'] ?? '', ['transmitir','cancelar','cartaCorrecao','inutilizar'], true);
        foreach ($pipes as $pipe) { stream_set_blocking($pipe, false); }
        try {
            while (true) {
                if ($cancelled && $cancelled()) { throw new AcbrException('OPERACAO_CANCELADA', 'Operação cancelada.', $uncertain || $sending); }
                if (hrtime(true) >= $deadline) { throw new AcbrException('TIMEOUT_MOTOR', 'O motor excedeu o prazo; não repita uma operação fiscal sem consultar sua situação.', $uncertain || $sending); }
                $read = [];
                foreach ([1, 2] as $fd) { if (isset($pipes[$fd]) && !feof($pipes[$fd])) { $read[] = $pipes[$fd]; } }
                $write = isset($pipes[0]) ? [$pipes[0]] : []; $except = [];
                if ($read || $write) {
                    $remaining = max(1, min(20000, (int)(($deadline - hrtime(true)) / 1000)));
                    $selected = @stream_select($read, $write, $except, 0, $remaining);
                    if ($selected === false) { throw new AcbrException('FALHA_PIPE', 'Falha na comunicação com o motor.', $uncertain || $sending); }
                    foreach ($write as $pipe) {
                        $written = @fwrite($pipe, substr($bytes, $offset, 65536));
                        if ($written === false) { throw new AcbrException('FALHA_PIPE', 'O motor fechou a entrada prematuramente.', $uncertain || $sending); }
                        $offset += $written;
                        if ($offset === strlen($bytes)) { fclose($pipes[0]); unset($pipes[0]); $bytes = ''; }
                    }
                    foreach ($read as $pipe) {
                        $chunk = fread($pipe, 65536);
                        if ($chunk === false) { throw new AcbrException('FALHA_PIPE', 'Falha ao ler o motor.', $uncertain || $sending); }
                        $total += strlen($chunk);
                        if ($total > $this->maxBytes) { throw new AcbrException('RESPOSTA_EXCESSIVA', 'Saída do motor excede o limite de bytes.', $uncertain || $sending); }
                        // stderr is drained and counted, never included in exceptions/logs.
                        if ($pipe !== $pipes[1]) { continue; }
                        $buffer .= $chunk;
                        while (($end = strpos($buffer, "\n")) !== false) {
                            $line = substr($buffer, 0, $end); $buffer = substr($buffer, $end + 1);
                            if ($line === '') { continue; }
                            try { $frame = json_decode($line, true, 64, JSON_THROW_ON_ERROR); }
                            catch (\JsonException) { throw new AcbrException('RESPOSTA_INVALIDA', 'JSON inválido na resposta do motor.', $uncertain || $sending); }
                            if (!is_array($frame) || array_is_list($frame) || ($frame['id'] ?? null) !== ($request['id'] ?? null)) { throw new AcbrException('RESPOSTA_INVALIDA', 'Identificador de resposta inválido.', $uncertain || $sending); }
                            if (($frame['tipo'] ?? null) === 'envioIniciado') {
                                if (($request['comando'] ?? null) === 'recuperarXml') { throw new AcbrException('RESPOSTA_INVALIDA', 'Recuperação de XML não pode iniciar envio fiscal.'); }
                                if ($result !== null || $sending) { throw new AcbrException('RESPOSTA_INVALIDA', 'Sequência de eventos inválida.', true); }
                                $sending = true; continue;
                            }
                            if ($result !== null || ($frame['versao'] ?? null) !== 1 || ($frame['sourceRevision'] ?? null) !== ($request['sourceRevision'] ?? null) || ($frame['contractHash'] ?? null) !== ($request['contractHash'] ?? null) || !is_bool($frame['sucesso'] ?? null) || !in_array($frame['estado'] ?? null, self::STATES, true)) { throw new AcbrException('RESPOSTA_INVALIDA', 'Contrato ou estado de resposta inválido.', $uncertain || $sending); }
                            if (array_key_exists('xmlBase64', $frame) && (!is_string($frame['xmlBase64']) || $frame['xmlBase64'] === '' || base64_encode(base64_decode($frame['xmlBase64'], true) ?: '') !== $frame['xmlBase64'])) { throw new AcbrException('RESPOSTA_INVALIDA', 'XML codificado inválido.', $uncertain || $sending); }
                            if (array_key_exists('cStat', $frame) && (!is_int($frame['cStat']) || $frame['cStat'] < 1 || $frame['cStat'] > 9999)) { throw new AcbrException('RESPOSTA_INVALIDA', 'Status fiscal inválido.', $uncertain || $sending); }
                            if (array_key_exists('envioIniciado', $frame) && (!is_bool($frame['envioIniciado']) || ($sending && !$frame['envioIniciado']))) { throw new AcbrException('RESPOSTA_INVALIDA', 'Status de envio inconsistente.', $uncertain || $sending); }
                            foreach (['codigo','xMotivo','chave','protocolo','recibo'] as $key) { if (array_key_exists($key, $frame) && !is_string($frame[$key])) { throw new AcbrException('RESPOSTA_INVALIDA', 'Campo de resposta inválido.', $uncertain || $sending); } }
                            if (($request['comando'] ?? null) === 'recuperarXml') { $this->recoveryFrame($frame); }
                            elseif (($frame['estado'] ?? null) === 'XML_RECUPERADO') { throw new AcbrException('RESPOSTA_INVALIDA', 'Estado de recuperação fora do comando correspondente.', $uncertain || $sending); }
                            if ($sending) { $frame['envioIniciado'] = true; }
                            $result = new FiscalResult($frame, $request['comando'] ?? null);
                        }
                    }
                } else { usleep(1000); }
                $status = proc_get_status($process);
                if (!$status['running']) {
                    $exit ??= $status['exitcode'];
                    if (feof($pipes[1]) && feof($pipes[2])) { break; }
                }
            }
            if ($exit !== 0 || $buffer !== '' || $result === null || isset($pipes[0])) { throw new AcbrException('FALHA_MOTOR', 'Motor encerrou sem resposta fiscal completa e válida.', $uncertain || $sending); }
            return $result;
        } catch (\Throwable $error) {
            $clean = $this->terminate($process);
            if (!$clean) {
                throw new AcbrException($error instanceof AcbrException ? $error->codigo : 'FALHA_MOTOR', 'Falha no motor; encerramento não confirmado. Interrompa o uso deste processo PHP.', $uncertain || $sending, false, ($request['comando'] ?? '') === 'transmitir' ? ($request['xmlBase64'] ?? null) : null);
            }
            if ($error instanceof AcbrException) {
                throw new AcbrException($error->codigo, $error->getMessage(), $error->resultadoDesconhecido, $error->limpezaConfirmada, ($error->resultadoDesconhecido && ($request['comando'] ?? '') === 'transmitir') ? ($request['xmlBase64'] ?? null) : null);
            }
            throw new AcbrException('FALHA_OPERACAO', 'Falha durante a operação; verifique a situação fiscal antes de repetir.', $uncertain || $sending, true, ($request['comando'] ?? '') === 'transmitir' ? ($request['xmlBase64'] ?? null) : null);
        } finally {
            $bytes = '';
            foreach ($pipes as $pipe) { if (is_resource($pipe)) { fclose($pipe); } }
            // Never call blocking proc_close while status reports a live worker.
            if (is_resource($process) && !proc_get_status($process)['running']) { proc_close($process); }
        }
    }

    private function terminate($process): bool
    {
        if (!proc_get_status($process)['running']) { return true; }
        // Only this proc_open handle is signalled; no PID search/group kill.
        @proc_terminate($process, 9);
        $deadline = hrtime(true) + 1000000000;
        do {
            if (!proc_get_status($process)['running']) { return true; }
            usleep(1000);
        } while (hrtime(true) < $deadline);
        return false;
    }

    private function recoveryFrame(#[\SensitiveParameter] array $frame): void
    {
        if (($frame['envioIniciado'] ?? null) !== false || (($frame['estado'] ?? null) === 'XML_RECUPERADO') !== $frame['sucesso']) {
            throw new AcbrException('RESPOSTA_INVALIDA', 'Estado ou envio incompatível com recuperação de XML.');
        }
        if (!$frame['sucesso'] && (!in_array($frame['estado'], ['ERRO','CONSULTADO'], true) || isset($frame['xmlBase64']) || isset($frame['protocolo']))) {
            throw new AcbrException('RESPOSTA_INVALIDA', 'Falha de recuperação com estado ou conteúdo de sucesso.');
        }
        if ($frame['sucesso'] && (($frame['cStat'] ?? null) !== 100 || !is_string($frame['chave'] ?? null) || preg_match('/\A[0-9]{44}\z/D', $frame['chave']) !== 1
            || !is_string($frame['protocolo'] ?? null) || preg_match('/\A[0-9]{15}\z/D', $frame['protocolo']) !== 1 || !isset($frame['xmlBase64']))) {
            throw new AcbrException('RESPOSTA_INVALIDA', 'Recuperação sem XML e protocolo de autorização válidos.');
        }
        if (isset($frame['xmlBase64'])) {
            $xml = base64_decode($frame['xmlBase64'], true);
            if (!is_string($xml) || strlen($xml) > 4194304 || preg_match('//u', $xml) !== 1 || str_contains($xml, "\0")) {
                throw new AcbrException('RESPOSTA_INVALIDA', 'XML recuperado inválido ou acima de 4 MiB.');
            }
        }
        if (($frame['estado'] ?? null) === 'CONSULTADO' && (($frame['codigo'] ?? null) !== 'RECUPERACAO_NAO_COMPROVADA' || !isset($frame['cStat']) || $frame['cStat'] === 100
            || !is_string($frame['chave'] ?? null) || preg_match('/\A[0-9]{44}\z/D', $frame['chave']) !== 1 || isset($frame['xmlBase64']) || isset($frame['protocolo']))) {
            throw new AcbrException('RESPOSTA_INVALIDA', 'Consulta de recuperação não comprovada com campos incoerentes.');
        }
    }
}
