<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';
use Acbr\AcbrException;
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Csc;
use Acbr\Emitter;
use Acbr\Engine;
use Acbr\FiscalResult;

final class RecordingEngine implements Engine
{
    public array $requests = [];
    public ?AcbrException $failure = null;
    public function execute(#[\SensitiveParameter] array $request, ?callable $cancelled = null): FiscalResult
    {
        $this->requests[] = $request;
        if ($this->failure) { throw $this->failure; }
        $states = ['gerarXml'=>'XML_GERADO','validar'=>'VALIDADO','assinar'=>'ASSINADO','transmitir'=>'AUTORIZADO','consultar'=>'CONSULTADO','cancelar'=>'CANCELADO','cartaCorrecao'=>'EVENTO_REGISTRADO','inutilizar'=>'INUTILIZADO'];
        return new FiscalResult(['versao'=>1,'id'=>$request['id'],'sourceRevision'=>$request['sourceRevision'],'contractHash'=>$request['contractHash'],'sucesso'=>true,'estado'=>$states[$request['comando']]]);
    }
}
$count = 0;
$check = static function (bool $ok, string $label) use (&$count) {
    if (!$ok) { throw new RuntimeException($label); } ++$count;
};
$fail = static function (callable $call, string $code) use ($check) {
    try { $call(); throw new RuntimeException('Expected '.$code); }
    catch (AcbrException $error) { $check($error->codigo === $code, 'Expected '.$code.' got '.$error->codigo); }
};
// These opaque strings only exercise wire serialization to an injected mock; no native certificate read.
$certificate = CertificateA1::fromData('MOCK_PFX_NOT_A_REAL_CERTIFICATE', 'MOCK_SECRET_DO_NOT_LOG');
$config = new Configuration('11222333000181','CE', certificado:$certificate, csc:new Csc('000001','MOCK_CSC'));
$mock = new RecordingEngine();
$emitter = new Emitter($config, engine:$mock);
$xml = '<NFe>synthetic unsigned test only</NFe>';
$key = str_repeat('1',44); $protocol = str_repeat('2',15);
$reason = 'JUSTIFICATIVA SINTETICA DE TESTE';
$correction = 'CORRECAO SINTETICA PARA TESTE LOCAL';
$cases = [
    ['gerarXml',[], 'XML_GERADO'],
    ['validar',$xml,'VALIDADO'],
    ['assinar',$xml,'ASSINADO'],
    ['transmitir',$xml,'AUTORIZADO'],
    ['consultar',['chave'=>$key,'recibo'=>$protocol],'CONSULTADO'],
    ['cancelar',['chave'=>$key,'protocolo'=>$protocol,'justificativa'=>$reason],'CANCELADO'],
    ['cartaCorrecao',['chave'=>$key,'correcao'=>$correction],'EVENTO_REGISTRADO'],
    ['inutilizar',['ano'=>26,'serie'=>1,'numeroInicial'=>1,'numeroFinal'=>2,'justificativa'=>$reason],'INUTILIZADO'],
];
$ids=[];
foreach ($cases as [$operation,$data,$state]) {
    $result = $emitter->$operation($data);
    $request = end($mock->requests);
    $check($result->estado()===$state && $request['comando']===$operation, $operation.' state');
    $check($request['versao']===1 && $request['sourceRevision']===48590 && $request['contractHash']===\Acbr\Contract::CONTRACT_HASH, $operation.' contract');
    $check($request['config']['certificado']===['pfxBase64'=>base64_encode('MOCK_PFX_NOT_A_REAL_CERTIFICATE'),'senha'=>'MOCK_SECRET_DO_NOT_LOG'], $operation.' certificate via wire');
    $check($request['config']['csc']===['id'=>'000001','valor'=>'MOCK_CSC'], $operation.' csc');
    $ids[]=$request['id'];
}
$check(count(array_unique($ids))===8, 'unique request IDs');
$check($mock->requests[3]['xmlBase64']===base64_encode($xml), 'xml base64');
$check($mock->requests[6]['sequenciaEvento']===1, 'CCe default sequence');
$check($mock->requests[7]['inutilizacao']===['ano'=>26,'serie'=>1,'numeroInicial'=>1,'numeroFinal'=>2], 'range nested protocol');
$check(!isset($mock->requests[7]['ano']), 'no misplaced range keys');
$before = count($mock->requests);
$fail(fn()=>$emitter->consultar(['chave'=>'1']), 'CHAVE_INVALIDA');
$fail(fn()=>$emitter->consultar(['chave'=>$key,'recibo'=>123]), 'RECIBO_INVALIDO');
$fail(fn()=>$emitter->consultar(['chave'=>$key,'config'=>[]]), 'OPERACAO_INVALIDA');
$fail(fn()=>$emitter->cancelar(['chave'=>$key,'protocolo'=>'bad','justificativa'=>$reason]), 'PROTOCOLO_INVALIDO');
foreach (['short',str_repeat('x',256),"\xff".str_repeat('x',20)] as $bad) {
    $fail(fn()=>$emitter->cancelar(['chave'=>$key,'protocolo'=>$protocol,'justificativa'=>$bad]), 'JUSTIFICATIVA_INVALIDA');
}
foreach ([0,21,'1',1.5] as $seq) {
    $fail(fn()=>$emitter->cartaCorrecao(['chave'=>$key,'correcao'=>$correction,'sequenciaEvento'=>$seq]), 'CORRECAO_INVALIDA');
}
foreach ([['ano'=>100],['ano'=>'26'],['serie'=>1000],['numeroInicial'=>0],['numeroFinal'=>0],['numeroFinal'=>1000000000]] as $change) {
    $fail(fn()=>$emitter->inutilizar(array_replace(['ano'=>26,'serie'=>1,'numeroInicial'=>1,'numeroFinal'=>2,'justificativa'=>$reason],$change)), 'INUTILIZACAO_INVALIDA');
}
foreach (['',"\xff",str_repeat('x',2097153)] as $bad) {
    $fail(fn()=>$emitter->transmitir($bad), 'XML_INVALIDO');
}
$fail(fn()=>$emitter->transmitir($xml, cancelled:static fn()=>true), 'OPERACAO_CANCELADA');
$check(count($mock->requests)===$before, 'invalid requests never invoke engine');
$nfce = new Emitter(new Configuration('11222333000181','CE',modelo:65,certificado:$certificate),engine:$mock);
$fail(fn()=>$nfce->cartaCorrecao(['chave'=>$key,'correcao'=>$correction]), 'OPERACAO_NAO_SUPORTADA');
$without = new Emitter(new Configuration('11222333000181','CE'),engine:$mock);
foreach (array_slice($cases,2) as [$operation,$data]) { $fail(fn()=>$without->$operation($data), 'CERTIFICADO_AUSENTE'); }
$mock->failure = new AcbrException('TIMEOUT_MOTOR','Prazo excedido.',true,true,base64_encode($xml));
$fail(fn()=>$emitter->transmitir($xml), 'TIMEOUT_MOTOR');
$check(count($mock->requests)===$before+1, 'uncertain send is never retried');
$check($mock->failure->xmlRecuperacao()===$xml, 'explicit recovery XML');
$check(!str_contains(json_encode($mock->failure),$xml) && !str_contains(print_r($mock->failure,true),$xml), 'recovery is redacted');
$mock->failure = new AcbrException('FALHA_MOTOR','Falha.',true,false);
$fail(fn()=>$emitter->transmitir($xml), 'FALHA_MOTOR');
$mock->failure = null;
$fail(fn()=>$emitter->transmitir($xml), 'EMISSOR_FECHADO');
foreach (['INDETERMINADO','PENDENTE'] as $state) {
    $check((new FiscalResult(['sucesso'=>false,'estado'=>$state]))->resultadoDesconhecido(), 'uncertain result state');
}
$check((new FiscalResult(['sucesso'=>true,'estado'=>'AUTORIZADO','cStat'=>100,'chave'=>$key,'protocolo'=>$protocol,'envioIniciado'=>true], 'transmitir'))->statusEnvio()==='autorizado','authorized status');
$check((new FiscalResult(['sucesso'=>false,'estado'=>'REJEITADO','cStat'=>225,'envioIniciado'=>true], 'transmitir'))->statusEnvio()==='rejeitado','rejected status');
$check((new FiscalResult(['sucesso'=>false,'estado'=>'ERRO','envioIniciado'=>true]))->resultadoDesconhecido(),'send error uncertain');
foreach ([['cancelar','CANCELADO',135],['cancelar','CANCELADO',136],['cartaCorrecao','EVENTO_REGISTRADO',135],['inutilizar','INUTILIZADO',102]] as [$command,$state,$code]) {
    $data=['sucesso'=>true,'estado'=>$state,'cStat'=>$code,'chave'=>$key,'protocolo'=>$protocol,'envioIniciado'=>true];
    $check((new FiscalResult($data,$command))->statusEnvio()==='autorizado','confirmed fiscal event '.$command);
    unset($data['protocolo']);
    $check((new FiscalResult($data,$command))->resultadoDesconhecido(),'missing event proof '.$command);
}
$check((new FiscalResult(['sucesso'=>true,'estado'=>'AUTORIZADO'], 'transmitir'))->resultadoDesconhecido(),'incomplete authorization proof');
$check((new FiscalResult(['sucesso'=>false,'estado'=>'CONSULTADO','cStat'=>217], 'consultar'))->resultadoDesconhecido(),'query not-found does not authorize retransmit');
$check((new FiscalResult(['sucesso'=>true,'estado'=>'XML_GERADO'], 'gerarXml'))->statusEnvio()==='nao_enviado','local operation scope');
ini_set('zend.exception_ignore_args','0');
try { $without->transmitir('<NFe>TRACE_FISCAL_SENTINEL</NFe>'); }
catch (AcbrException $error) { $check(!str_contains(print_r($error->getTrace(),true),'TRACE_FISCAL_SENTINEL'),'operation trace redaction'); }
$pipeEngine = new class implements Engine {
    public function execute(#[\SensitiveParameter] array $request, ?callable $cancelled = null): FiscalResult
    {
        return (new \Acbr\ProcessTransport(2000))->execute(__DIR__.'/fixtures/mock-worker',__DIR__,['PATH'=>'/usr/bin:/bin','ACBR_TEST_MODE'=>'fiscal'],$request,$cancelled);
    }
};
$pipeEmitter = new Emitter($config,engine:$pipeEngine);
foreach ($cases as [$operation,$data,$state]) {
    $result = $pipeEmitter->$operation($data);
    $check($result->sucesso(), 'JSONL subprocess wire '.$operation);
    $check($result->statusEnvio()===(in_array($operation,['gerarXml','validar','assinar'],true)?'nao_enviado':'autorizado'), 'wire fiscal status '.$operation);
}
echo "Fiscal operations: {$count} assertions passed; injected mock only, no network\n";
