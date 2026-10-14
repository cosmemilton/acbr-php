<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Acbr\Contract;
use Acbr\Document;
use Acbr\Model\FieldHelpException;
use Acbr\Model\TACBrTipoAmbiente;
use Acbr\Model\TDetCollection;
use Acbr\Model\TDetCollectionItem;
use Acbr\Model\TIde;
use Acbr\Model\TNFe;
use Acbr\Model\ValidationException;

$checks = 0;
function verify(bool $condition, string $message): void
{
    global $checks;
    ++$checks;
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
function reject(callable $operation, string $code, ?string $field = null): void
{
    try {
        $operation();
    } catch (ValidationException $error) {
        verify($error->codigo === $code, 'Incorrect validation code: ' . $error->codigo . ' expected ' . $code);
        if ($field !== null) {
            verify($error->campo === $field, 'Incorrect validation path.');
        }
        return;
    }
    throw new RuntimeException('Invalid input was accepted: ' . $code);
}
function helpReject(callable $operation, string $code): void
{
    try {
        $operation();
    } catch (FieldHelpException $error) {
        verify($error->codigo === $code, 'Incorrect help error code.');
        return;
    }
    throw new RuntimeException('Invalid field query was accepted.');
}

$contract = Contract::bundled();
verify(count($contract->types()) === 204, 'All upstream types must be available.');
verify($contract->metadata()['contractHash'] === Contract::CONTRACT_HASH, 'Pinned hash mismatch.');
verify($contract->metadata()['sourceRevision'] === 48590, 'Pinned revision mismatch.');
verify(count($contract->fields()) === 24, 'Unexpected root fields.');
verify($contract->help('Ide.tpAmb')['values'] === ['taProducao', 'taHomologacao'], 'Enum help mismatch.');
verify($contract->help('Det[12].Prod.vProd')['path'] === 'TNFe.Det[].Prod.vProd', 'Canonical collection path mismatch.');
verify($contract->help('Det[].Prod.vProd')['constraints']['scale'] === 4, 'Currency scale missing.');
verify($contract->help('TNFe.Det[]')['pascalType'] === 'TDetCollectionItem', 'Collection item help mismatch.');
verify($contract->help('Det')['itemType'] === 'TDetCollectionItem', 'Collection help mismatch.');
verify($contract->help('TIde')['kind'] === 'object', 'Named type help mismatch.');
verify($contract->help('TACBrTipoAmbiente')['kind'] === 'enum', 'Named enum help mismatch.');
verify($contract->help('signature')['readonly'], 'Read-only field help missing.');
verify($contract->help('signature.DigestValue')['readonly'], 'Read-only ancestor must propagate.');
verify(str_contains($contract->formatHelp('Ide.tpAmb'), 'taHomologacao'), 'Text help must include values.');
helpReject(fn () => $contract->help('Det.Prod'), 'COLECAO_REQUER_INDICE');
helpReject(fn () => $contract->help('Ide[]'), 'CAMPO_NAO_COLECAO');
helpReject(fn () => $contract->help('Ide.tpAmb.x'), 'CAMINHO_INVALIDO');
helpReject(fn () => $contract->help('Ide.doesNotExist'), 'CAMPO_INEXISTENTE');
helpReject(fn () => $contract->help('Det[-1]'), 'CAMINHO_INVALIDO');
helpReject(fn () => $contract->help('CNPJCPF'), 'CAMPO_AMBIGUO');
reject(fn () => $contract->validate([], 'UnknownModel'), 'MODELO_DESCONHECIDO');
reject(fn () => $contract->validate(null), 'OBJETO_ESPERADO', 'TNFe');
reject(fn () => $contract->validate(['secret-value-in-unknown-key' => 'never-log-me']), 'CAMPO_DESCONHECIDO', 'TNFe');
try {
    $contract->validate(['secret-value-in-unknown-key' => 'never-log-me']);
} catch (ValidationException $error) {
    verify(!str_contains(json_encode($error, JSON_THROW_ON_ERROR), 'secret-value'), 'Unknown keys must not leak.');
    verify(!str_contains($error->getMessage(), 'never-log-me'), 'Input values must not leak.');
}
reject(fn () => new Document(['signature' => []]), 'CAMPO_SOMENTE_LEITURA', 'TNFe.signature');
reject(fn () => new Document(['Ide' => ['tpAmb' => 'homologacao']]), 'ENUM_INVALIDO', 'TNFe.Ide.tpAmb');
reject(fn () => new Document(['Ide' => ['nNF' => '1']]), 'INTEIRO_INVALIDO', 'TNFe.Ide.nNF');
reject(fn () => new Document(['Ide' => ['nNF' => 1.0]]), 'INTEIRO_INVALIDO');
reject(fn () => new Document(['Ide' => ['nNF' => 2147483648]]), 'INTEIRO_INVALIDO');
reject(fn () => new Document(['Ide' => ['nNF' => true]]), 'INTEIRO_INVALIDO');
reject(fn () => new Document(['Ide' => ['natOp' => 12]]), 'TEXTO_ESPERADO');
reject(fn () => new Document(['Det' => ['named' => []]]), 'ARRAY_ESPERADO');
reject(fn () => new Document(['Det' => [false]]), 'OBJETO_ESPERADO', 'TNFe.Det[0]');
reject(fn () => new Document(['Ide' => [1, 2]]), 'OBJETO_ESPERADO');
reject(fn () => new Document(['Ide' => new DateTimeImmutable()]), 'OBJETO_ESPERADO');
foreach ([1.25, 1, true, null, '1,25', '1e2', '+1', ' 1', '1 ', '.1', '1.', 'NaN', 'INF', "1\n", '1.12345', str_repeat('1', 65)] as $invalid) {
    reject(fn () => new Document(['Det' => [['Prod' => ['vProd' => $invalid]]]]), 'DECIMAL_INVALIDO', 'TNFe.Det[0].Prod.vProd');
}
foreach (['0', '-1', '0001.2500', '1.2345', str_repeat('1', 64)] as $valid) {
    $document = new Document(['Det' => [['Prod' => ['vProd' => $valid]]]]);
    verify($document->toArray()['Det'][0]['Prod']['vProd'] === $valid, 'Decimals must remain exact strings.');
}
foreach (['2026-10-14T12:00:00Z', '2026-10-14T12:00:00-03:00', '2026-10-14 12:00:00', '2026-10-14T12:00', '2026-10-14T12:00:00.1234', "2026-10-14\n", 123] as $invalid) {
    reject(fn () => new Document(['Ide' => ['dEmi' => $invalid]]), 'DATA_HORA_INVALIDA');
}
foreach (['2026-02-29', '2026-02-30', '2026-00-01', '2026-13-01', '2026-10-00', '0000-01-01', '2026-10-14T24:00:00', '2026-10-14T12:60:00', '2026-10-14T12:00:60'] as $invalid) {
    reject(fn () => new Document(['Ide' => ['dEmi' => $invalid]]), 'DATA_INVALIDA');
}
foreach (['2024-02-29', '2026-10-14', '2026-10-14T12:00:00', '2026-10-14T12:00:00.001', '0001-01-01', '9999-12-31'] as $valid) {
    date_default_timezone_set('Pacific/Kiritimati');
    $a = new Document(['Ide' => ['dEmi' => $valid]]);
    date_default_timezone_set('America/Sao_Paulo');
    $b = new Document(['Ide' => ['dEmi' => $valid]]);
    verify($a->toJson() === $b->toJson() && $a->toArray()['Ide']['dEmi'] === $valid, 'Civil dates must never change timezone.');
}
verify((new Document())->toJson() === '{}', 'Empty root must serialize as object.');
verify((new Document(['Ide' => [], 'Det' => []]))->toJson() === '{"Ide":{},"Det":[]}', 'Nested empty objects and lists must remain distinct.');
verify(Document::fromJson('{"Ide":{},"Det":[{}]}')->toJson() === '{"Ide":{},"Det":[{}]}', 'JSON roundtrip must preserve empty objects.');
reject(fn () => Document::fromJson('[]'), 'OBJETO_ESPERADO');
reject(fn () => Document::fromJson('{"Ide":[]}'), 'OBJETO_ESPERADO');
reject(fn () => Document::fromJson('{"Det":{}}'), 'ARRAY_ESPERADO');
reject(fn () => Document::fromJson('{"Det":[[]]}'), 'OBJETO_ESPERADO');
reject(fn () => Document::fromJson('{"Ide":{"nNF":1.0}}'), 'INTEIRO_INVALIDO');
$source = (object) ['Ide' => (object) ['natOp' => 'Original']];
$original = new TNFe($source);
$source->Ide->natOp = 'Changed source';
$copy = $original->jsonSerialize();
$copy->Ide->natOp = 'Changed copy';
verify($original->getIde()->getNatOp() === 'Original', 'Document must be detached from all external objects.');
$changed = $original->withIde(['natOp' => 'Other']);
verify($changed instanceof TNFe && $changed->getIde()->getNatOp() === 'Other', 'with() must retain concrete type.');
verify($original->getIde()->getNatOp() === 'Original', 'with() must not mutate original.');
verify($changed->without('Ide')->toJson() === '{}', 'without() must remove only selected fields.');
$ide = (new TIde())->withTpAmb(TACBrTipoAmbiente::taHomologacao)->withNNF(12);
$note = (new TNFe())->withIde($ide)->withDet((new TDetCollection())->withItem(new TDetCollectionItem()));
verify($note->getIde()->getTpAmb() === TACBrTipoAmbiente::taHomologacao, 'Typed enum roundtrip failed.');
verify($note->getDet()->get(0) instanceof TDetCollectionItem, 'Typed collection hydration failed.');
verify(count($note->getDet()) === 1 && count($note->getDet()->without(0)) === 0, 'Immutable collection operation failed.');
verify(count(iterator_to_array($note->getDet())) === 1, 'Collection iterator failed.');
reject(fn () => (new TNFe())->withIde(new TDetCollectionItem()), 'OBJETO_ESPERADO');
reject(fn () => (new \Acbr\Model\TProd())->withVProd(1.25), 'DECIMAL_INVALIDO');

// Exercise every generated type and every property, not only the visible examples.
foreach ($contract->types() as $type) {
    $class = 'Acbr\\Model\\' . $type['name'];
    verify(class_exists($class) || enum_exists($class), 'Generated type missing: ' . $type['name']);
    if ($type['kind'] === 'enum') {
        verify(array_map(static fn (BackedEnum $case): string => $case->value, $class::cases()) === $type['values'], 'Generated enum drift.');
        continue;
    }
    $model = new $class();
    verify($model->typeName() === $type['name'], 'Generated model type mismatch.');
    if ($type['kind'] === 'list') {
        verify($model->toJson() === '[]', 'Empty collection mismatch.');
        verify($model->withItem([])->get(0)->typeName() === $type['item'], 'Collection item class mismatch.');
        continue;
    }
    verify($model->toJson() === '{}', 'Empty object mismatch.');
    foreach ($type['properties'] as $property) {
        $getter = 'get' . ucfirst($property['name']);
        $setter = 'with' . ucfirst($property['name']);
        verify($model->$getter() === null, 'Omitted fields must remain absent.');
        if ($property['readonly']) {
            verify(!method_exists($model, $setter), 'Read-only fields must not have a setter.');
            reject(fn () => new $class([$property['name'] => null]), 'CAMPO_SOMENTE_LEITURA');
            continue;
        }
        $sample = match ($property['kind']) {
            'object', 'list' => [],
            'integer' => 0,
            'boolean' => true,
            'enum' => $contract->type($property['ref'])['values'][0],
            'decimal' => '0.00',
            'datetime' => '2026-10-14T12:34:56',
            default => 'text',
        };
        $updated = $model->$setter($sample);
        verify($updated->has($property['name']) && $updated->$getter() !== null, 'Generated field roundtrip failed.');
        verify(!$model->has($property['name']), 'Generated setter mutated original.');
        $contract->validate($updated, $type['name']);
    }
}

foreach ([new TNFe(), new TDetCollection()] as $immutable) {
    try {
        $immutable->newField = 1;
        throw new RuntimeException('Dynamic model mutation was accepted.');
    } catch (LogicException $error) {
        verify(str_contains($error->getMessage(), 'imutáveis'), 'Model mutation must be rejected.');
    }
    foreach ([JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT] as $flag) {
        try {
            $immutable->toJson($flag);
            throw new RuntimeException('Shape-changing JSON option was accepted.');
        } catch (InvalidArgumentException $error) {
            verify(str_contains($error->getMessage(), 'JSON'), 'Shape-changing JSON options must be rejected.');
        }
    }
}

// PHP 8.2+ must redact payloads even when the deployment records trace arguments.
$previousTraceSetting = ini_get('zend.exception_ignore_args');
ini_set('zend.exception_ignore_args', '0');
$sentinel = 'PRIVATE_DOCUMENT_SENTINEL_846129';
foreach (['constructor', 'fromArray', 'fromJson', 'malformedJson', 'invalidUtf8', 'setter', 'collection', 'collectionJson', 'validate', 'normalize', 'help', 'field'] as $scenario) {
    try {
        switch ($scenario) {
            case 'constructor': new Document(['Ide' => ['nNF' => $sentinel]]); break;
            case 'fromArray': Document::fromArray([$sentinel => $sentinel]); break;
            case 'fromJson': Document::fromJson('{"Ide":{"nNF":"' . $sentinel . '"}}'); break;
            case 'malformedJson': Document::fromJson('{"' . $sentinel . '":'); break;
            case 'invalidUtf8': new Document(['Ide' => ['natOp' => $sentinel . "\xff"]]); break;
            case 'setter': (new \Acbr\Model\TProd())->withVProd($sentinel); break;
            case 'collection': (new TDetCollection())->withItem([$sentinel => $sentinel]); break;
            case 'collectionJson': TDetCollection::fromJson('["' . $sentinel . '",'); break;
            case 'validate': $contract->validate(['Ide' => ['nNF' => $sentinel]]); break;
            case 'normalize': $contract->normalize(['Ide' => ['nNF' => $sentinel]]); break;
            case 'help': $contract->help($sentinel . '[]'); break;
            case 'field': (new TNFe())->get($sentinel); break;
        }
        throw new RuntimeException('Invalid trace fixture was accepted.');
    } catch (ValidationException|FieldHelpException $error) {
        verify(!str_contains(print_r($error, true), $sentinel), 'Exception object leaked submitted input: ' . $scenario);
        verify(!str_contains($error->getTraceAsString(), $sentinel), 'Exception trace leaked submitted input: ' . $scenario);
        verify(!str_contains((string) $error, $sentinel), 'Exception string leaked submitted input: ' . $scenario);
    }
}
ini_set('zend.exception_ignore_args', $previousTraceSetting);

$metadata = $contract->metadata();
$metadata['ir']['types'][0]['values'][0] = 'tampered';
$temporary = tempnam(sys_get_temp_dir(), 'acbr-contract-');
try {
    file_put_contents($temporary, json_encode($metadata, JSON_THROW_ON_ERROR));
    try {
        new Contract($temporary);
        throw new RuntimeException('Tampered contract was accepted.');
    } catch (RuntimeException $error) {
        verify(str_contains($error->getMessage(), 'Metadados'), 'Contract tampering must fail closed.');
    }
} finally {
    unlink($temporary);
}
fwrite(STDOUT, 'Contract/model checks passed: ' . $checks . ".\n");
