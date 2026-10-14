<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
use Acbr\Configuration;
use Acbr\Document;
use Acbr\Emitter;
$runtime = getenv('ACBR_RUNTIME_DIR') ?: \Acbr\NativeRuntime::defaultDirectory();
$document = Document::fromJson(file_get_contents(dirname(__DIR__).'/tests/fixtures/documento-sintetico.json'));
$emitter = new Emitter(new Configuration('11222333000181', 'CE', modelo:55, ambiente:2), $runtime);
$result = $emitter->gerarXml($document);
if (!$result->sucesso()) { throw new RuntimeException(json_encode($result, JSON_THROW_ON_ERROR)); }
echo $result->xml();
