<?php
declare(strict_types=1);
/** Real native integration; exclusively gerarXml, assinar and validar. Never SEFAZ. */
require __DIR__ . '/bootstrap.php';

use Acbr\AcbrException;
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Csc;
use Acbr\Document;
use Acbr\Emitter;
use Acbr\FiscalResult;

$runtime = getenv('ACBR_RUNTIME_DIR');
if (!$runtime) { fwrite(STDERR,"Set ACBR_RUNTIME_DIR to the verified Linux runtime. This test never calls SEFAZ.\n"); exit(2); }
foreach (['openssl','dom'] as $extension) {
    if (!extension_loaded($extension)) { fwrite(STDERR,"Offline signing test requires ext-{$extension}; normal SDK use does not.\n"); exit(2); }
}
$checks = 0;
function signingCheck(bool $condition, string $description): void
{
    global $checks;
    if (!$condition) { throw new RuntimeException($description); }
    ++$checks;
}
function signingSuccess(FiscalResult $result, string $state): void
{
    signingCheck($result->sucesso() && $result->estado() === $state, 'Expected ' . $state . ': ' . json_encode($result));
    signingCheck(($result->data()['envioIniciado'] ?? false) === false, 'Offline operation must not mark transmission started');
}
function signingFailure(callable $operation, string $code): void
{
    try {
        $result = $operation();
        signingCheck(!$result->sucesso() && ($result->data()['codigo'] ?? null) === $code, 'Expected ' . $code . ': ' . json_encode($result));
        signingCheck(($result->data()['envioIniciado'] ?? false) === false, 'Failure must remain offline');
    } catch (AcbrException $error) {
        signingCheck($error->codigo === $code && !$error->resultadoDesconhecido, 'Expected safe ' . $code . ', got ' . $error->codigo);
    }
}

/** Synthetic self-signed A1, fresh private key, never a real or ICP-Brasil certificate. */
function syntheticCertificate(string $directory, string $cnpj, #[SensitiveParameter] string $password, int $days = 2): array
{
    $path = $directory . '/synthetic-openssl.cnf';
    $text = "[ req ]\ndistinguished_name = dn\nx509_extensions = extensions\nprompt = no\n" .
        "[ dn ]\nCN = ACBR PHP SYNTHETIC:{$cnpj}\nO = SYNTHETIC OFFLINE TEST\nC = BR\n" .
        "[ extensions ]\nbasicConstraints = critical,CA:FALSE\n" .
        "keyUsage = critical,digitalSignature,nonRepudiation,keyEncipherment\nextendedKeyUsage = clientAuth\n" .
        "subjectAltName = otherName:2.16.76.1.3.3;UTF8:{$cnpj}\n";
    signingCheck(file_put_contents($path,$text) !== false, 'Write synthetic OpenSSL configuration');
    chmod($path,0600);
    $options = ['config'=>$path, 'digest_alg'=>'sha256', 'private_key_type'=>OPENSSL_KEYTYPE_RSA,
        'private_key_bits'=>2048, 'x509_extensions'=>'extensions'];
    $key = openssl_pkey_new($options);
    signingCheck($key !== false, 'Generate ephemeral RSA key');
    $csr = openssl_csr_new(['commonName'=>'ACBR PHP SYNTHETIC:' . $cnpj,
        'organizationName'=>'SYNTHETIC OFFLINE TEST', 'countryName'=>'BR'], $key, $options);
    signingCheck($csr !== false, 'Create synthetic CSR');
    $certificate = openssl_csr_sign($csr,null,$key,$days,$options,random_int(1,PHP_INT_MAX));
    signingCheck($certificate !== false, 'Create synthetic self-signed certificate');
    $pfx = ''; $pem = '';
    signingCheck(openssl_pkcs12_export($certificate,$pfx,$key,$password), 'Export ephemeral PFX in memory');
    signingCheck(openssl_x509_export($certificate,$pem), 'Export public certificate');
    return ['pfx'=>$pfx, 'pem'=>$pem];
}

/** Independent XMLDSig verification with PHP DOM C14N and OpenSSL, not the signer. */
function verifySyntheticSignature(#[SensitiveParameter] string $xml, string $expectedPem): DOMXPath
{
    $document = new DOMDocument();
    signingCheck($document->loadXML($xml,LIBXML_NONET), 'Parse signed XML without network');
    $xpath = new DOMXPath($document);
    $xpath->registerNamespace('nfe','http://www.portalfiscal.inf.br/nfe');
    $xpath->registerNamespace('ds','http://www.w3.org/2000/09/xmldsig#');
    signingCheck($xpath->query('/nfe:NFe/ds:Signature')->length === 1, 'Exactly one document signature');
    $info = $xpath->query('/nfe:NFe/ds:Signature/ds:SignedInfo')->item(0);
    $fiscal = $xpath->query('/nfe:NFe/nfe:infNFe')->item(0);
    signingCheck($info instanceof DOMElement && $fiscal instanceof DOMElement, 'SignedInfo and infNFe present');
    signingCheck($xpath->evaluate('string(//ds:CanonicalizationMethod/@Algorithm)') === 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315', 'Pinned inclusive canonicalization');
    signingCheck($xpath->evaluate('string(//ds:SignatureMethod/@Algorithm)') === 'http://www.w3.org/2000/09/xmldsig#rsa-sha1', 'Pinned native XML signature algorithm');
    signingCheck($xpath->query('//ds:Reference')->length === 1 && $xpath->evaluate('string(//ds:Reference/@URI)') === '#' . $fiscal->getAttribute('Id'), 'Reference targets the sole fiscal document');
    signingCheck($xpath->evaluate('string(//ds:DigestMethod/@Algorithm)') === 'http://www.w3.org/2000/09/xmldsig#sha1', 'Pinned native XML digest algorithm');
    signingCheck($xpath->query('//ds:Transform')->length === 2
        && $xpath->evaluate('string((//ds:Transform)[1]/@Algorithm)') === 'http://www.w3.org/2000/09/xmldsig#enveloped-signature'
        && $xpath->evaluate('string((//ds:Transform)[2]/@Algorithm)') === 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315', 'Pinned transform sequence');
    signingCheck(hash_equals($xpath->evaluate('string(//ds:DigestValue)'),base64_encode(sha1($fiscal->C14N(false,false),true))), 'Independent fiscal digest matches');
    $der = base64_decode($xpath->evaluate('string(//ds:X509Certificate)'),true);
    signingCheck(is_string($der) && $der !== '', 'Signature embeds a certificate');
    $actualPem = "-----BEGIN CERTIFICATE-----\n" . chunk_split(base64_encode($der),64,"\n") . "-----END CERTIFICATE-----\n";
    signingCheck(openssl_x509_fingerprint($actualPem,'sha256') === openssl_x509_fingerprint($expectedPem,'sha256'), 'Signer is exactly the generated synthetic certificate');
    $signature = base64_decode($xpath->evaluate('string(//ds:SignatureValue)'),true);
    signingCheck(is_string($signature) && openssl_verify($info->C14N(false,false),$signature,$expectedPem,OPENSSL_ALGO_SHA1) === 1, 'Independent RSA signature verification');
    return $xpath;
}

$temporary = sys_get_temp_dir() . '/acbr-php-signing-test-' . bin2hex(random_bytes(8));
signingCheck(mkdir($temporary,0700), 'Create private temporary fixture directory');
$password = ' synthetic-only ç ' . bin2hex(random_bytes(16)) . ' ';
$cscValue = 'CSC-SINTETICO-OFFLINE-NAO-TRANSMITIR';
$emitters = [];
try {
    $synthetic = syntheticCertificate($temporary,'11222333000181',$password);
    $pfxPath = $temporary . '/ephemeral-synthetic.pfx';
    signingCheck(file_put_contents($pfxPath,$synthetic['pfx']) !== false, 'Write only ephemeral synthetic PFX');
    chmod($pfxPath,0600);
    signingCheck((fileperms($temporary) & 0777) === 0700 && (fileperms($pfxPath) & 0777) === 0600, 'Temporary fixture permissions are private');
    $certificate = CertificateA1::fromFile($pfxPath,$password);
    unlink($pfxPath); // The wrapper owns a memory snapshot, not an open file/path.
    $csc = new Csc('000001',$cscValue);
    $unsigned = [];
    foreach ([55=>'documento-sintetico.json',65=>'documento-nfce-sintetico.json'] as $model=>$fixture) {
        $emitter = new Emitter(new Configuration('11222333000181','CE',$model,2,$certificate,$model===65?$csc:null),$runtime,timeoutMs:20000);
        $emitters[] = $emitter;
        $document = Document::fromJson(file_get_contents(__DIR__ . '/fixtures/' . $fixture));
        $generated = $emitter->gerarXml($document);
        signingSuccess($generated,'XML_GERADO');
        $unsigned[$model] = $generated->xml();
        foreach (['<mod>' . $model . '</mod>','<tpAmb>2</tpAmb>','<vProd>10.00</vProd>','Café São José'] as $expected) {
            signingCheck(str_contains($unsigned[$model],$expected), 'Generated fixture retains expected fiscal/UTF-8 content');
        }
        signingCheck(!str_contains($unsigned[$model],'<Signature'), 'Generation does not sign');
        signingFailure(fn () => $emitter->validar($unsigned[$model]),'XML_SCHEMA_INVALIDO');
        $signed = $emitter->assinar($unsigned[$model]);
        signingSuccess($signed,'ASSINADO');
        signingCheck($signed->data()['chave'] === $generated->data()['chave'], 'Signing preserves fiscal identity');
        $xml = $signed->xml();
        $xpath = verifySyntheticSignature($xml,$synthetic['pem']);
        if ($model === 65) {
            signingCheck($xpath->evaluate('string(/nfe:NFe/nfe:infNFe/nfe:ide/nfe:tpImp)') === '4', 'Dedicated NFC-e fixture uses tpImp 4');
            $prefix = $signed->data()['chave'] . '|2|2|1';
            $expectedQr = 'http://nfceh.sefaz.ce.gov.br/pages/ShowNFCe.html?p=' . $prefix . '|' . strtoupper(sha1($prefix . $cscValue));
            signingCheck($xpath->evaluate('string(/nfe:NFe/nfe:infNFeSupl/nfe:qrCode)') === $expectedQr, 'Exact pinned CE homologation QR v2, normalized CSC ID and independent token hash');
            signingCheck($xpath->evaluate('string(/nfe:NFe/nfe:infNFeSupl/nfe:urlChave)') !== '', 'NFC-e consultation URL present');
            signingCheck(!str_contains($xml,$cscValue), 'QR never exposes literal CSC secret');
        } else {
            signingCheck($xpath->query('/nfe:NFe/nfe:infNFeSupl')->length === 0, 'NF-e has no NFC-e supplement');
        }
        $validator = new Emitter(new Configuration('11222333000181','CE',$model),$runtime,timeoutMs:20000);
        $emitters[] = $validator;
        signingSuccess($validator->validar($xml),'VALIDADO');
        $tampered = str_replace('<vNF>10.00</vNF>','<vNF>11.00</vNF>',$xml);
        signingCheck($tampered !== $xml, 'Tamper test actually changes signed content');
        signingFailure(fn () => $validator->validar($tampered),'ASSINATURA_INVALIDA');
        signingFailure(fn () => $validator->assinar($unsigned[$model]),'CERTIFICADO_AUSENTE');
    }

    $withoutCsc = new Emitter(new Configuration('11222333000181','CE',65,2,$certificate),$runtime,timeoutMs:20000);
    $emitters[] = $withoutCsc;
    signingFailure(fn () => $withoutCsc->assinar($unsigned[65]),'CSC_AUSENTE');
    $wrongPassword = new Emitter(new Configuration('11222333000181','CE',55,2,CertificateA1::fromData($synthetic['pfx'],'deliberately-wrong')),$runtime,timeoutMs:20000);
    $emitters[] = $wrongPassword;
    signingFailure(fn () => $wrongPassword->assinar($unsigned[55]),'CERTIFICADO_INVALIDO');
    $invalidPfx = new Emitter(new Configuration('11222333000181','CE',55,2,CertificateA1::fromData('not a PFX',$password)),$runtime,timeoutMs:20000);
    $emitters[] = $invalidPfx;
    signingFailure(fn () => $invalidPfx->assinar($unsigned[55]),'CERTIFICADO_INVALIDO');
    $mismatch = syntheticCertificate($temporary,'12345678000195',$password);
    $wrongRoot = new Emitter(new Configuration('11222333000181','CE',55,2,CertificateA1::fromData($mismatch['pfx'],$password)),$runtime,timeoutMs:20000);
    $emitters[] = $wrongRoot;
    signingFailure(fn () => $wrongRoot->assinar($unsigned[55]),'CERTIFICADO_DIVERGENTE');
    $expired = syntheticCertificate($temporary,'11222333000181',$password,0);
    sleep(2); // A zero-day self-signed fixture must be past its second-resolution notAfter.
    $expiredEmitter = new Emitter(new Configuration('11222333000181','CE',55,2,CertificateA1::fromData($expired['pfx'],$password)),$runtime,timeoutMs:20000);
    $emitters[] = $expiredEmitter;
    signingFailure(fn () => $expiredEmitter->assinar($unsigned[55]),'CERTIFICADO_INVALIDO');

    // The native protocol uses byte lengths, unlike the Node facade's UTF-16 check.
    // Exercise raw native requests here so PHP's early CSC rejection cannot hide a mismatch.
    $resolved = \Acbr\NativeRuntime::resolve($runtime);
    $transport = new \Acbr\ProcessTransport(20000);
    $contract = \Acbr\Contract::bundled();
    $nfce = $contract->normalize(json_decode(file_get_contents(__DIR__ . '/fixtures/documento-nfce-sintetico.json'),true,64,JSON_THROW_ON_ERROR));
    foreach ([str_repeat('ç',128),str_repeat('ç',129),str_repeat('ç',256),str_repeat('🔒',64),str_repeat('🔒',65)] as $syntheticToken) {
        $request = ['versao'=>1, 'id'=>'csc-boundary', 'comando'=>'gerarXml',
            'sourceRevision'=>$contract->metadata()['sourceRevision'], 'contractHash'=>$contract->metadata()['contractHash'],
            'config'=>['cnpj'=>'11222333000181','uf'=>'CE','modelo'=>65,'ambiente'=>2,'csc'=>['id'=>'000001','valor'=>$syntheticToken]],
            'documento'=>$nfce];
        $run = fn () => $transport->execute($resolved->executable,$resolved->directory,$resolved->environment(),$request);
        if (strlen($syntheticToken) <= 256) { signingSuccess($run(),'XML_GERADO'); }
        else { signingFailure($run,'REQUISICAO_INVALIDA'); }
    }
} finally {
    foreach ($emitters as $emitter) { $emitter->fechar(); }
    foreach (glob($temporary . '/*') as $file) { unlink($file); }
    rmdir($temporary);
}
echo "Real native offline signing/NF-e/NFC-e: {$checks} assertions passed; ephemeral synthetic A1 only, independent XMLDSig/QR checks, no SEFAZ calls\n";
