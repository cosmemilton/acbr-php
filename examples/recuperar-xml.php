<?php
declare(strict_types=1);

use Acbr\Emitter;
use Acbr\FiscalResult;

/**
 * Call explicitly with an Emitter configured by your secure credential provider.
 * This operation consults the configured fiscal service. Never call implicitly.
 * XML and retornoOriginal()/data() are private; only jsonSerialize() is diagnostic.
 */
function recuperarXmlParaArquivoPrivado(
    Emitter $emissor,
    #[SensitiveParameter] string $xmlOriginalAssinado,
    #[SensitiveParameter] string $arquivoDestinoNovo,
): FiscalResult {
    $resultado = $emissor->recuperarXml($xmlOriginalAssinado);
    if (!$resultado->sucesso() || $resultado->estado() !== 'XML_RECUPERADO') {
        return $resultado;
    }
    $xml = $resultado->xml();
    if (!is_string($xml) || $xml === '') { throw new RuntimeException('XML recuperado ausente.'); }
    // The caller chooses a private directory. Never replace the original signed XML.
    $handle = @fopen($arquivoDestinoNovo, 'xb');
    if ($handle === false) { throw new RuntimeException('Destino existente ou indisponível.'); }
    try {
        for ($offset = 0, $length = strlen($xml); $offset < $length; $offset += $written) {
            $written = fwrite($handle, substr($xml, $offset));
            if ($written === false || $written === 0) { throw new RuntimeException('Falha ao gravar XML recuperado.'); }
        }
    } finally { fclose($handle); }
    return $resultado;
}

// Include this helper from your application. Direct execution never reads A1 or connects.
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    fwrite(STDERR, "Inclua este exemplo e chame a função explicitamente com seu Emitter e destino privado.\n");
    exit(2);
}
