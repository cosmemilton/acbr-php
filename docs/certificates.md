# Certificado A1 e CSC

O SDK recebe A1 em PKCS#12 (`.pfx` ou `.p12`) e encaminha os bytes e a senha ao
motor nativo pelo `stdin`. A assinatura é local. Um resultado `ASSINADO` ou
`VALIDADO` não significa autorização pela SEFAZ, homologação de produção nem
validade jurídica de um certificado de teste.

## Configuração

```php
use Acbr\CertificateA1;
use Acbr\Configuration;
use Acbr\Csc;
use Acbr\Emitter;

// Obtenha senha e CSC do cofre de segredos da aplicação; nunca do repositório.
$a1 = CertificateA1::fromFile('/caminho/privado/empresa.pfx', $senhaDoCofre);
// Alternativa: bytes binários PKCS#12 já obtidos pela aplicação, não Base64.
// $a1 = CertificateA1::fromData($bytesPfx, $senhaDoCofre);

$configuration = new Configuration(
    cnpj: '11222333000181',
    uf: 'CE',
    modelo: 65,
    ambiente: 2,
    certificado: $a1,
    csc: new Csc('000001', $cscDoCofre),
);
$emitter = new Emitter($configuration, '/caminho/runtime-verificado');
try {
    $result = $emitter->assinar($xmlGerado);
    if (!$result->sucesso()) {
        // JSON padrão contém códigos/estado seguros, não o XML.
        throw new RuntimeException(json_encode($result, JSON_THROW_ON_ERROR));
    }
    $xmlAssinado = $result->xml(); // conteúdo fiscal: armazene com acesso restrito.
} finally {
    $emitter->fechar();
}
```

O CNPJ acima é apenas um identificador sintético de exemplo. A configuração e o
documento devem identificar o mesmo emitente, UF, modelo e ambiente. Para NF-e
modelo 55, omita o CSC. NFC-e modelo 65 exige CSC ao assinar; seu valor e ID devem
corresponder à credencial da UF e do ambiente da operação. A biblioteca não
descobre, cadastra nem valida o CSC em um serviço externo.

## Validação e limites

- `fromFile` lê uma única vez um arquivo regular local, sem URL ou stream wrapper,
  e mantém um snapshot em memória. Nenhum caminho de certificado é enviado ao motor.
- `fromData` recebe bytes binários. Entrada vazia é rejeitada e o limite é
  2 MiB, inclusive. A leitura de arquivo é limitada a 2 MiB + 1 para detectar
  crescimento do arquivo sem uma leitura ilimitada.
- Senha é texto UTF-8, pode estar vazia e não é aparada. Espaços nas extremidades
  são preservados. O wrapper não tenta interpretar PKCS#12 em PHP.
- O CSC tem ID textual de 1 a 6 dígitos, preservando zeros à esquerda no protocolo,
  e valor UTF-8 não vazio de até 256 bytes, conforme o protocolo do motor nativo.
  Há uma diferença no cliente Node: seu filtro inicial conta unidades UTF-16 e
  pode aceitar texto que o motor rejeita. O PHP aplica o limite nativo antes do
  envio: 128 caracteres `ç` (256 bytes) são aceitos; 129 (258 bytes) são rejeitados.
- A estrutura enviada é exatamente `certificado: {pfxBase64, senha}` e
  `csc: {id, valor}`. `hasCertificate()` e `hasCsc()` expõem somente presença.
- O motor verifica se o A1 abre com a senha, se a raiz CNPJ corresponde ao emitente
  e se está no período de validade. Essas verificações não constituem uma auditoria
  completa de cadeia ICP-Brasil, revogação ou elegibilidade fiscal. O teste aceita
  deliberadamente um certificado autoassinado sintético.
- Erros incluem `CERTIFICADO_AUSENTE`, `CERTIFICADO_EXCESSIVO`,
  `CERTIFICADO_INVALIDO`, `CERTIFICADO_DIVERGENTE`, `CSC_INVALIDO` e `CSC_AUSENTE`.
  Falhas de configuração PHP lançam `AcbrException`; rejeições nativas retornam
  `FiscalResult` sem sucesso e com o código correspondente.

## Tratamento dos segredos

Certificado, senha e CSC ficam em propriedades privadas. `json_encode`,
`var_dump` e `print_r` dos objetos de configuração não exibem os segredos.
Parâmetros sensíveis usam `#[SensitiveParameter]` para a representação dos
argumentos nas stack traces em PHP 8.2+. A serialização PHP com `serialize` é
bloqueada; reconstrua a configuração a partir do cofre ao iniciar a aplicação.

`toWire()` é uma saída explícita e secreta destinada apenas ao pipe do motor.
Não registre esse array, XML, bytes PFX, senha, token ou variáveis locais em logs,
APM, dumps de processo ou mensagens de erro. O acesso explícito de código à
memória, reflexão, `var_export` e depuradores não é uma fronteira de segurança.
Strings PHP podem ser copiadas pelo runtime; o SDK não promete zeragem segura
da memória. `fechar()` fecha o emissor, mas não apaga objetos de configuração que
a aplicação ainda mantém. Proteja também permissões, processo PHP, backups e
armazenamento de XML.

## Evidência offline reproduzível

```sh
php tests/configuration-test.php
ACBR_RUNTIME_DIR=/caminho/runtime-verificado php tests/signing-offline.php
```

A segunda suíte exige `ext-openssl` e `ext-dom` apenas no ambiente de testes e
gera chaves RSA e certificados A1 autoassinados novos a cada execução. O PFX
sintético é escrito somente em diretório temporário `0700`, arquivo `0600`,
removido após a leitura. A chave privada não é gravada separadamente. Um bloco
`finally` remove os demais arquivos de teste. Nenhum certificado real ou senha
real é necessário, persistido ou distribuído nas fixtures.

A suíte usa o executável fixado Linux x64 0.1.2, sem recompilar ou substituir o
motor, e chama exclusivamente `gerarXml`, `assinar` e `validar`:

- NF-e 55 e NFC-e 65 com valores decimais e texto UTF-8;
- identidade fiscal preservada e XML assinado validado pelo motor;
- verificação independente de XMLDSig/digest com DOM C14N e OpenSSL PHP,
  conferindo também que o certificado assinado é exatamente o gerado pelo teste;
- rejeição de XML adulterado, senha errada, PFX inválido, raiz CNPJ divergente,
  certificado expirado e ausência de A1 ou CSC;
- QR Code NFC-e versão 2 da configuração CE/homologação do runtime fixado,
  ID `000001` normalizado pelo motor para `1` e hash SHA-1 recalculado com o CSC;
- limite nativo CSC verificado com texto não ASCII: aceita exatamente 256 bytes
  UTF-8 e rejeita 258, 260 e 512 bytes, mesmo quando o filtro Node aceitaria o valor.

O XML contém URLs fiscais como dados. Os testes não abrem essas URLs nem chamam
transmissão, consulta, cancelamento, carta de correção ou inutilização. O QR
comprovado é específico do runtime/UF/ambiente fixados; não comprova credenciais
reais nem disponibilidade atual de serviços. O motor fixado usa RSA-SHA1 e
C14N inclusivo no XMLDSig fiscal; a suíte verifica esse comportamento existente,
sem substituir algoritmos definidos pelo motor.

Não há suporte ou testes reivindicados para A3, Windows, ARM, Alpine/musl,
DANFE/impressão, validação ICP-Brasil completa ou homologação real SEFAZ.
