<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
use Acbr\Configuration;
use Acbr\Document;
use Acbr\Emitter;
$runtime = getenv('ACBR_RUNTIME_DIR');
if (!$runtime) { fwrite(STDERR, "Set ACBR_RUNTIME_DIR to the verified Linux runtime. No network calls are made.\n"); exit(2); }
$doc = Document::fromJson(file_get_contents(__DIR__.'/fixtures/documento-sintetico.json'));
$emitter = new Emitter(new Configuration('11222333000181','CE'), $runtime, timeoutMs:10000);
$result = $emitter->gerarXml($doc);
if (!$result->sucesso() || $result->estado() !== 'XML_GERADO' || !$result->xml()) { throw new RuntimeException('Native XML generation failed: '.json_encode($result)); }
$xml = $result->xml();
foreach (['Café São José', '<vProd>10.00</vProd>', '<mod>55</mod>', '<tpAmb>2</tpAmb>'] as $expected) {
    if (!str_contains($xml, $expected)) { throw new RuntimeException('Missing expected synthetic XML content'); }
}
$validation = $emitter->validar($xml);
// Unsigned XML cannot pass the signed NF-e schema. Native must reject cleanly.
if ($validation->sucesso() || $validation->estado() !== 'ERRO') { throw new RuntimeException('Unsigned fixture unexpectedly validated'); }
$invalid = $emitter->validar('<NFe>invalid</NFe>');
if ($invalid->sucesso()) { throw new RuntimeException('Malformed XML unexpectedly validated'); }
$emitter->fechar();
try { $emitter->gerarXml($doc); throw new RuntimeException('Closed emitter accepted work'); }
catch (\Acbr\AcbrException $e) { if ($e->codigo !== 'EMISSOR_FECHADO') { throw $e; } }
echo "Native offline: generated synthetic NF-e, exact decimal/UTF-8 content, rejected unsigned and malformed XML, closed emitter verified\n";
