<?php
declare(strict_types=1);
/**
 * Real native recovery integration, exclusively synthetic A1 and HTTPS localhost.
 * Linux prerequisites: PHP openssl/dom, fpc 3.2.2, Lazarus 3.0 units, tar and the
 * verified production runtime 0.2.1 including its source archive. No apt/npm.
 * Run: ACBR_RUNTIME_DIR=/absolute/runtime/acbr-php-0.2.1 php tests/recovery-native-offline.php
 * Builds an isolated -dACBR_NODE_TEST variant from unchanged published sources
 * in a fresh /tmp tree. Never alters the production worker, inventory, CA store
 * or service URLs. Test-only service INI accepts HTTPS localhost exclusively.
 * All private keys/passwords are newly generated synthetic fixtures; the A1
 * travels in memory/stdin, never argv/env. Own PHP server/workers only.
 */
require __DIR__.'/bootstrap.php';

use Acbr\AcbrException;
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Document;
use Acbr\Emitter;
use Acbr\Engine;
use Acbr\FiscalResult;
use Acbr\NativeRuntime;
use Acbr\ProcessTransport;

function recoveryServer(string $directory): never {
    if (!str_starts_with($directory,sys_get_temp_dir().'/acbr-php-recovery-native-') || is_link($directory)) { exit(2); }
    $context=stream_context_create(['ssl'=>['local_cert'=>$directory.'/server.pem','local_pk'=>$directory.'/server.key',
        'verify_peer'=>false,'crypto_method'=>STREAM_CRYPTO_METHOD_TLSv1_2_SERVER]]);
    $server=@stream_socket_server('tls://127.0.0.1:0',$error,$message,STREAM_SERVER_BIND|STREAM_SERVER_LISTEN,$context);
    if (!is_resource($server)) { exit(3); }
    $name=stream_socket_get_name($server,false); file_put_contents($directory.'/ready.json',json_encode(['port'=>(int)substr($name,strrpos($name,':')+1)],JSON_THROW_ON_ERROR));
    while (!is_file($directory.'/stop')) {
        $client=@stream_socket_accept($server,0.2); if (!is_resource($client)) { continue; } stream_set_timeout($client,3);
        $head=''; while (!str_contains($head,"\r\n\r\n") && strlen($head)<65536 && !feof($client)) { $line=fgets($client); if ($line===false) { break; } $head.=$line; }
        preg_match('/(?:^|\r\n)Content-Length:\s*(\d+)/i',$head,$match); $remaining=(int)($match[1]??0); $body='';
        while ($remaining>0 && !feof($client)) { $part=fread($client,min(8192,$remaining)); if ($part===false || $part==='') { break; } $body.=$part; $remaining-=strlen($part); }
        file_put_contents($directory.'/requests.jsonl',json_encode(['body'=>$body],JSON_THROW_ON_ERROR)."\n",FILE_APPEND|LOCK_EX);
        $response=file_get_contents($directory.'/response.xml');
        $soap='<?xml version="1.0" encoding="UTF-8"?><soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope"><soap:Body><nfeResultMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NFeConsultaProtocolo4">'.$response.'</nfeResultMsg></soap:Body></soap:Envelope>';
        $wire="HTTP/1.1 200 OK\r\nContent-Type: application/soap+xml; charset=utf-8\r\nContent-Length: ".strlen($soap)."\r\nConnection: close\r\n\r\n".$soap;
        $offset=0; while ($offset<strlen($wire)) { $n=@fwrite($client,substr($wire,$offset)); if (!is_int($n) || $n<1) { break; } $offset+=$n; } fclose($client);
    }
    fclose($server); exit(0);
}
if (($argv[1]??'')==='--synthetic-local-server') { recoveryServer($argv[2]??''); }

$recoveryChecks=0;
function recoveryCheck(bool $condition,string $name): void { global $recoveryChecks; if (!$condition) { throw new RuntimeException($name); } ++$recoveryChecks; }
function recoveryWrite(string $path,string $bytes): void { recoveryCheck(file_put_contents($path,$bytes)===strlen($bytes),'Write synthetic fixture'); chmod($path,0600); }
function recoveryCommand(array $command,string $cwd,string $log): void {
    $process=proc_open($command,[0=>['pipe','r'],1=>['file',$log,'a'],2=>['redirect',1]],$pipes,$cwd,null,['bypass_shell'=>true]);
    recoveryCheck(is_resource($process),'Start owned fixture build process'); fclose($pipes[0]);
    recoveryCheck(proc_close($process)===0,'Fixture build failed; inspect private temporary compiler log');
}
function recoveryDirectories(string $root): array {
    $out=[$root]; foreach (new DirectoryIterator($root) as $item) { if (!$item->isDot() && $item->isDir() && !$item->isLink()) { array_push($out,...recoveryDirectories($item->getPathname())); } } sort($out,SORT_STRING); return $out;
}
function recoveryCleanup(string $directory): void {
    recoveryCheck(str_starts_with($directory,sys_get_temp_dir().'/acbr-php-recovery-native-') && !is_link($directory),'Cleanup owned temporary tree only');
    $items=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($items as $item) { if ($item->isDir() && !$item->isLink()) { rmdir($item->getPathname()); } else { unlink($item->getPathname()); } } rmdir($directory);
}
function recoveryCertificate(string $directory,string $purpose,string $cnpj=''): array {
    $client=$purpose==='client'; $cn=$client?'ACBR PHP SYNTHETIC:'.$cnpj:'localhost';
    $extensions=$client?"basicConstraints=critical,CA:FALSE\nkeyUsage=critical,digitalSignature,nonRepudiation,keyEncipherment\nextendedKeyUsage=clientAuth\nsubjectAltName=otherName:2.16.76.1.3.3;UTF8:{$cnpj}\n":
        "basicConstraints=critical,CA:TRUE\nkeyUsage=critical,digitalSignature,keyEncipherment,keyCertSign\nextendedKeyUsage=serverAuth\nsubjectAltName=DNS:localhost\n";
    $config=$directory.'/'.$purpose.'.cnf'; recoveryWrite($config,"[req]\ndistinguished_name=dn\nx509_extensions=extensions\nprompt=no\n[dn]\nCN={$cn}\nO=SYNTHETIC OFFLINE TEST\nC=BR\n[extensions]\n".$extensions);
    $options=['config'=>$config,'digest_alg'=>'sha256','private_key_type'=>OPENSSL_KEYTYPE_RSA,'private_key_bits'=>2048,'x509_extensions'=>'extensions'];
    $key=openssl_pkey_new($options); recoveryCheck($key!==false,'Synthetic RSA key');
    $csr=openssl_csr_new(['commonName'=>$cn,'organizationName'=>'SYNTHETIC OFFLINE TEST','countryName'=>'BR'],$key,$options); recoveryCheck($csr!==false,'Synthetic CSR');
    $cert=openssl_csr_sign($csr,null,$key,2,$options,random_int(1,1000000)); recoveryCheck($cert!==false,'Synthetic certificate');
    $pem=''; $private=''; recoveryCheck(openssl_x509_export($cert,$pem) && openssl_pkey_export($key,$private),'Export synthetic public/server material');
    return ['certificate'=>$cert,'key'=>$key,'pem'=>$pem,'private'=>$private];
}
function recoveryXpath(string $xml): DOMXPath {
    recoveryCheck(!str_contains(strtoupper($xml),'<!DOCTYPE') && !str_contains(strtoupper($xml),'<!ENTITY'),'No DTD/entities');
    $doc=new DOMDocument(); recoveryCheck($doc->loadXML($xml,LIBXML_NONET),'Parse synthetic XML');
    $x=new DOMXPath($doc); $x->registerNamespace('n','http://www.portalfiscal.inf.br/nfe'); $x->registerNamespace('ds','http://www.w3.org/2000/09/xmldsig#'); return $x;
}
function recoveryProof(DOMXPath $x,string $prefix,string $pem): array {
    $fiscal=$x->query($prefix.'/n:infNFe')->item(0); $info=$x->query($prefix.'/ds:Signature/ds:SignedInfo')->item(0);
    recoveryCheck($fiscal instanceof DOMElement && $info instanceof DOMElement,'Unique original signed fiscal nodes');
    $digest=$x->evaluate('string('.$prefix.'/ds:Signature/ds:SignedInfo/ds:Reference/ds:DigestValue)');
    $signature=base64_decode($x->evaluate('string('.$prefix.'/ds:Signature/ds:SignatureValue)'),true);
    recoveryCheck(hash_equals($digest,base64_encode(sha1($fiscal->C14N(false,false),true))),'Independent fiscal digest');
    recoveryCheck(is_string($signature) && openssl_verify($info->C14N(false,false),$signature,$pem,OPENSSL_ALGO_SHA1)===1,'Independent RSA signature');
    return ['fiscal'=>hash('sha256',$fiscal->C14N(false,false)),'signedInfo'=>hash('sha256',$info->C14N(false,false)),
        'signature'=>hash('sha256',$signature),'certificate'=>$x->evaluate('string('.$prefix.'/ds:Signature/ds:KeyInfo/ds:X509Data/ds:X509Certificate)')];
}
function recoveryResponse(string $key,string $digest,int $code=100,bool $protocol=true,?string $returnedKey=null): string {
    $time=gmdate('Y-m-d\TH:i:s').'-00:00'; $reason=$code===100?'Autorizado o uso da NF-e':'NF-e nao consta na base de dados';
    $prot=$protocol?'<protNFe versao="4.00"><infProt><tpAmb>2</tpAmb><verAplic>SYNTHETIC</verAplic><chNFe>'.$key.'</chNFe><dhRecbto>'.$time.'</dhRecbto><nProt>123456789012345</nProt><digVal>'.$digest.'</digVal><cStat>'.$code.'</cStat><xMotivo>'.$reason.'</xMotivo></infProt></protNFe>':'';
    return '<retConsSitNFe versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe"><tpAmb>2</tpAmb><verAplic>SYNTHETIC</verAplic><cStat>'.$code.'</cStat><xMotivo>'.$reason.'</xMotivo><cUF>23</cUF><dhRecbto>'.$time.'</dhRecbto><chNFe>'.($returnedKey??$key).'</chNFe>'.$prot.'</retConsSitNFe>';
}
final class RecoveryFixtureEngine implements Engine {
    private readonly ProcessTransport $transport;
    public function __construct(private string $executable,private string $directory,private array $environment) { $this->transport=new ProcessTransport(20000); }
    public function execute(#[SensitiveParameter] array $request,#[SensitiveParameter] ?callable $cancelled=null): FiscalResult { return $this->transport->execute($this->executable,$this->directory,$this->environment,$request,$cancelled); }
}

if (PHP_OS_FAMILY!=='Linux' || !extension_loaded('openssl') || !extension_loaded('dom') || !function_exists('stream_socket_server')) { fwrite(STDERR,"Requires Linux PHP openssl/dom and local TLS streams.\n"); exit(2); }
$runtime=getenv('ACBR_RUNTIME_DIR'); if (!$runtime) { fwrite(STDERR,"Set ACBR_RUNTIME_DIR to the verified production0.2.0 runtime; this test only contacts localhost.\n"); exit(2); }
$temporary=sys_get_temp_dir().'/acbr-php-recovery-native-'.bin2hex(random_bytes(8)); $server=null; $emitter=null; $serverPipes=[]; $completed=false;
recoveryCheck(mkdir($temporary,0700),'Private owned test directory');
try {
    $production=NativeRuntime::resolve($runtime); $productionManifestHash=hash_file('sha256',$runtime.'/manifest.json'); $productionWorkerHash=hash_file('sha256',$production->executable);
    $manifest=json_decode(file_get_contents($runtime.'/manifest.json'),true,32,JSON_THROW_ON_ERROR); recoveryCheck(in_array('recuperarXml',$manifest['capabilities']??[],true),'Production capability is pinned');
    $source=$temporary.'/source'; mkdir($source,0700); $build=$temporary.'/runtime'; mkdir($build,0700); $units=$temporary.'/units'; mkdir($units,0700);
    recoveryCommand(['tar','-xzf',$runtime.'/sources/acbr-source.tar.gz','-C',$source],$temporary,$temporary.'/compiler.log');
    $fonts=recoveryDirectories($source.'/Fontes'); $native=$runtime.'/sources/native';
    $includes=array_merge([$native],$fonts,['/usr/lib/lazarus/3.0/components/lazutils/lib/x86_64-linux','/usr/lib/lazarus/3.0/lcl/units/x86_64-linux',
        '/usr/lib/lazarus/3.0/lcl/units/x86_64-linux/nogui','/usr/lib/lazarus/3.0/packager/units/x86_64-linux']);
    $flags=['-vew','-Mdelphi','-Scghi','-O1','-g-','-dNOGUI','-dNOREPORT','-dACBR_NODE_TEST'];
    foreach ($includes as $path) { recoveryCheck(!preg_match('/[\s\"]/', $path),'Fixture compiler paths have no whitespace/quotes'); $flags[]='-Fu'.$path; }
    foreach ($fonts as $path) { $flags[]='-Fi'.$path; }
    array_push($flags,'-FU'.$units,'-FE'.$build,'-o'.$build.'/acbr-worker-test'); recoveryWrite($temporary.'/compiler.rsp',implode("\n",$flags)."\n");
    recoveryCommand(['fpc','@'.$temporary.'/compiler.rsp',$native.'/acbr-worker.lpr'],$temporary,$temporary.'/compiler.log');
    recoveryCheck(symlink($runtime.'/schemas',$build.'/schemas'),'Read-only schema reference to pinned production files');
    recoveryCheck(symlink($runtime.'/ACBrNFeServicos.ini',$build.'/ACBrNFeServicos.ini'),'Read-only service inventory required before test-only localhost override');
    $tls=recoveryCertificate($temporary,'server'); recoveryWrite($temporary.'/server.pem',$tls['pem']); recoveryWrite($temporary.'/server.key',$tls['private']);
    $client=recoveryCertificate($temporary,'client','11222333000181'); $password=' SYNTHETIC-ONLY '.bin2hex(random_bytes(16)).' '; $pfx='';
    recoveryCheck(openssl_pkcs12_export($client['certificate'],$pfx,$client['key'],$password),'Ephemeral synthetic A1 in memory');
    $server=proc_open([PHP_BINARY,__FILE__,'--synthetic-local-server',$temporary],[0=>['pipe','r'],1=>['file',$temporary.'/server.log','a'],2=>['redirect',1]],$serverPipes,$temporary,null,['bypass_shell'=>true]);
    recoveryCheck(is_resource($server),'Start owned PHP TLS fixture'); fclose($serverPipes[0]);
    $deadline=microtime(true)+5; while (!is_file($temporary.'/ready.json') && microtime(true)<$deadline) { usleep(1000); }
    recoveryCheck(is_file($temporary.'/ready.json'),'Local TLS fixture became ready'); $port=json_decode(file_get_contents($temporary.'/ready.json'),true,16,JSON_THROW_ON_ERROR)['port'];
    // TestServices also inspects commented URL assignments; redirect those as upstream does.
    $ini=preg_replace('/^([^\r\n=]*=[ \t]*)(?:https?:\/\/|www\.)[^\r\n]+/mi','$1https://localhost:'.$port.'/mock',file_get_contents($runtime.'/ACBrNFeServicos.ini'));
    $allLocal=true; foreach (preg_split('/\r?\n/',$ini) as $line) { $equals=strpos($line,'='); if ($equals===false) { continue; } $value=strtolower(trim(substr($line,$equals+1))); if (str_contains($value,'://') && !str_starts_with($value,'https://localhost:')) { $allLocal=false; } }
    recoveryCheck($allLocal,'Every service URL, including commented assignments, is HTTPS localhost');
    recoveryWrite($temporary.'/services.ini',$ini);
    $environment=$production->environment()+['ACBR_NODE_TEST_CA_FILE'=>$temporary.'/server.pem','ACBR_NODE_TEST_SERVICES'=>$temporary.'/services.ini'];
    $engine=new RecoveryFixtureEngine($build.'/acbr-worker-test',$build,$environment);
    $emitter=new Emitter(new Configuration('11222333000181','CE',55,2,CertificateA1::fromData($pfx,$password)),engine:$engine);
    $generated=$emitter->gerarXml(Document::fromJson(file_get_contents(__DIR__.'/fixtures/documento-sintetico.json')));
    recoveryCheck($generated->sucesso(),'Generate only synthetic source offline: '.json_encode($generated));
    $signedResult=$emitter->assinar($generated->xml()); recoveryCheck($signedResult->sucesso() && $signedResult->estado()==='ASSINADO','Real native synthetic signing: '.json_encode($signedResult)); $signed=$signedResult->xml();
    $x=recoveryXpath($signed); $key=substr($x->evaluate('string(/n:NFe/n:infNFe/@Id)'),3); $digest=$x->evaluate('string(//ds:DigestValue)'); $before=recoveryProof($x,'/n:NFe',$client['pem']);
    recoveryWrite($temporary.'/signed-original.xml',$signed); $sourceHash=hash('sha256',$signed);
    recoveryWrite($temporary.'/response.xml',recoveryResponse($key,$digest));
    $result=$emitter->recuperarXml($signed); recoveryCheck($result->sucesso() && $result->estado()==='XML_RECUPERADO' && $result->data()['cStat']===100,'Real native recovery success: '.json_encode($result));
    recoveryCheck(($result->data()['envioIniciado']??false)===false && $result->data()['chave']===$key && $result->data()['protocolo']==='123456789012345','Recovery is a correlated query, never emission');
    $recovered=$result->xml(); $after=recoveryXpath($recovered); recoveryCheck($before===recoveryProof($after,'/n:nfeProc/n:NFe',$client['pem']),'Original signature, signer and C14N content retained');
    recoveryCheck($after->document->schemaValidate($runtime.'/schemas/procNFe_v4.00.xsd'),'Independent procNFe4 schema');
    recoveryCheck(str_contains($recovered,substr($signed,strpos($signed,'<NFe'))),'Original signed NFe source bytes preserved');
    $requests=file($temporary.'/requests.jsonl',FILE_IGNORE_NEW_LINES); recoveryCheck(count($requests)===1,'Exactly one localhost query for positive recovery');
    $body=json_decode($requests[0],true,16,JSON_THROW_ON_ERROR)['body']; recoveryCheck(str_contains($body,$key) && !str_contains($body,'<infNFe') && !str_contains($body,'<NFe '),'Query sends only key, never original document');
    foreach ([['response'=>recoveryResponse($key,$digest,217,false),'code'=>'RECUPERACAO_NAO_COMPROVADA','state'=>'CONSULTADO','stat'=>217],
        ['response'=>recoveryResponse($key,$digest,100,false),'code'=>'RECUPERACAO_NAO_COMPROVADA'],
        ['response'=>recoveryResponse($key,$digest,100,true,str_repeat('1',44)),'code'=>'IDENTIDADE_DIVERGENTE'],
        ['response'=>recoveryResponse($key,base64_encode(str_repeat('x',20))),'code'=>'IDENTIDADE_DIVERGENTE']] as $case) {
        $count=count(file($temporary.'/requests.jsonl')); recoveryWrite($temporary.'/response.xml',$case['response']); $failure=$emitter->recuperarXml($signed);
        recoveryCheck(!$failure->sucesso() && ($failure->data()['codigo']??null)===$case['code'],'Native rejects unproved recovery');
        recoveryCheck(($failure->data()['envioIniciado']??false)===false && $failure->xml()===null && !isset($failure->data()['protocolo']),'No process or protocol fabricated on failed recovery');
        recoveryCheck(count(file($temporary.'/requests.jsonl'))===$count+1,'No automatic query retry on rejection');
        if (isset($case['state'])) { recoveryCheck($failure->estado()===$case['state'] && $failure->data()['cStat']===$case['stat'],'Non100 original query status remains available'); }
    }
    $count=count(file($temporary.'/requests.jsonl')); $tampered=str_replace('<vProd>10.00</vProd>','<vProd>11.00</vProd>',$signed,$changes); recoveryCheck($changes>0,'Tamper only synthetic signed fixture');
    $failure=$emitter->recuperarXml($tampered); recoveryCheck(!$failure->sucesso() && ($failure->data()['codigo']??null)==='ASSINATURA_INVALIDA','Invalid original signature rejected');
    recoveryCheck(count(file($temporary.'/requests.jsonl'))===$count,'Invalid signature rejected before network');
    recoveryCheck(hash_file('sha256',$temporary.'/signed-original.xml')===$sourceHash,'Original fixture file remains byte-identical');
    recoveryCheck(hash_file('sha256',$runtime.'/manifest.json')===$productionManifestHash && hash_file('sha256',$production->executable)===$productionWorkerHash,'Production manifest and binary remain unchanged');
    NativeRuntime::resolve($runtime); // All production inventory hashes still match after compilation/testing.
    $completed=true;
    echo "Native recovery: {$recoveryChecks} assertions passed; actual test-only worker+PHP transport, synthetic A1 and strict HTTPS localhost; no SEFAZ or production changes.\n";
} finally {
    if ($emitter!==null) { $emitter->fechar(); }
    if (is_resource($server)) { file_put_contents($temporary.'/stop','stop'); $deadline=microtime(true)+3; do { $status=proc_get_status($server); if (!$status['running']) { break; } usleep(10000); } while (microtime(true)<$deadline); if ($status['running']) { proc_terminate($server,9); } proc_close($server); }
    if (is_dir($temporary) && $completed) { recoveryCleanup($temporary); }
    elseif (is_dir($temporary)) { fwrite(STDERR,"Synthetic fixture diagnostics retained in {$temporary}; no real certificate/data used.\n"); }
}
