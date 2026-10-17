# ACBr PHP

Cliente PHP independente de Laravel/Symfony para o motor nativo público ACBr NF-e/NFC-e.
**SDK PHP 0.1.0-alpha2, uma pré-release.** O roteiro de homologação NF-e 55 e os
limites das provas executadas estão registrados em [verificação](docs/verification.md).

- PHP 8.2+, Composer, sem dependências de framework ou FFI.
- Motor **Linux x64 com glibc**, fixado no runtime 0.2.1; sem Windows, ARM ou Alpine/musl.
- Classes de configuração, documento, resultado e erro; 204 modelos gerados do contrato original.
- Nove comandos nativos: gerarXml, validar, assinar, transmitir, consultar, cancelar, cartaCorrecao, inutilizar e recuperarXml, sempre um processo por operação.
- Prazo, cancelamento cooperativo, limites de bytes, pipes não bloqueantes, drenagem de stderr e encerramento do processo pertencente à chamada.
- Help de tipos/campos, validação estrutural e instalador nativo com SHA-256, inventário fechado e rejeição de symlinks.
- A1 e CSC protegidos; testes de assinatura usam somente certificados sintéticos descartáveis.
- Fila assíncrona opcional para Linux com pcntl/posix; API síncrona sem essas extensões.
- Os testes automatizados não chamam a SEFAZ nem usam certificado real.

## Instalação no consumidor (Composer)

O pacote [cosmemilton/acbr-php](https://packagist.org/packages/cosmemilton/acbr-php)
está cadastrado no Packagist. Selecione explicitamente a pré-release do SDK:

    composer require cosmemilton/acbr-php:0.1.0-alpha2
    php vendor/bin/acbr-php runtime:release

O SDK PHP **0.1.0-alpha2** e o runtime nativo **0.2.1** têm versões independentes.
A tag do SDK disponibiliza a versão correspondente pelo Composer. Os comandos
abaixo são executados na raiz da aplicação consumidora.

Baixe o arquivo fixado; o instalador não faz downloads nem executa scripts externos:

    curl --fail --location --proto '=https' \
      'https://registry.npmjs.org/cosmemilton-acbr-node-linux-x64/-/cosmemilton-acbr-node-linux-x64-0.2.1.tgz' \
      --output /tmp/acbr-linux-x64-0.2.1.tgz
    mkdir -p var
    php vendor/bin/acbr-php runtime:install /tmp/acbr-linux-x64-0.2.1.tgz ./var/acbr-runtime-0.2.1
    export ACBR_RUNTIME_DIR="$PWD/var/acbr-runtime-0.2.1"
    php vendor/bin/acbr-php runtime:verify
    php vendor/bin/acbr-php runtime:diagnose

O instalador compara tamanho e SHA-256 do arquivo com o pin do pacote antes de extrair;
também valida o manifesto fixado e todos os arquivos. Preserva bibliotecas, schemas,
serviços, módulos OpenSSL, fontes e licenças. Não sobrescreve destinos existentes.
A instalação de .tgz precisa de ext-zlib. Veja [runtime nativo](docs/native-runtime.md).

Use ACBR_RUNTIME_DIR=/caminho/absoluto ou passe a pasta ao Emitter para uma instalação externa.
Nunca coloque o runtime em uma pasta gravável por usuários não confiáveis.
Verificação de integridade não substitui permissões de implantação nem protege contra
um atacante que já possa alterar o próprio pacote PHP.

### Checkout de desenvolvimento

Na raiz de um clone deste repositório, instale suas dependências e use a CLI local:

    composer install
    php bin/acbr-php runtime:release
    php bin/acbr-php runtime:diagnose

Nesse checkout, substitua `php vendor/bin/acbr-php` por `php bin/acbr-php` nos
comandos de instalação/verificação acima. `tests/fixtures`, `examples` e `scripts`
nas instruções de desenvolvimento são relativos à raiz do repositório; uma
aplicação consumidora deve fornecer seus próprios arquivos e caminhos.

## Uso offline

    require 'vendor/autoload.php';

    use Acbr\Configuration;
    use Acbr\Document;
    use Acbr\Emitter;

    $caminhoDocumento = '/caminho/da-aplicacao/documento.json';
    $jsonDocumento = file_get_contents($caminhoDocumento);
    $documento = Document::fromJson($jsonDocumento);
    $emissor = new Emitter(
        new Configuration('11222333000181', 'CE', modelo: 55, ambiente: 2),
        timeoutMs: 10_000,
    );
    $resultado = $emissor->gerarXml($documento);
    if ($resultado->sucesso()) {
        $xml = $resultado->xml(); // acesso explícito: conteúdo fiscal potencialmente sensível
    }
    $emissor->fechar();

O caminho do exemplo pertence à aplicação. Em um checkout de desenvolvimento,
`tests/fixtures/documento-sintetico.json` fornece uma fixture sintética, sem valor
fiscal, que nunca deve ser transmitida.
A validação estrutural PHP verifica os tipos do contrato; não substitui regras fiscais.
Um XML gerado sem assinatura pode ser rejeitado pelo schema NF-e que exige assinatura; isso é esperado.

### Ajuda pela CLI

Na aplicação consumidora, consulte campos e tipos sem runtime, A1 ou rede:

    php vendor/bin/acbr-php --version
    php vendor/bin/acbr-php --help
    php vendor/bin/acbr-php describe --help
    php vendor/bin/acbr-php describe Ide.tpNF
    php vendor/bin/acbr-php describe TTipoNFe
    php vendor/bin/acbr-php describe 'Det[].Prod.vProd' --json
    php vendor/bin/acbr-php ajuda Ide.tpNF

`describe` usa texto em português; `--json` seleciona metadados estruturados.
`ajuda` retorna JSON diretamente. Sem alvo, ambos descrevem `TNFe`.
Os comandos `runtime:*` conservam sua saída JSON e oferecem `--help` próprio.
No checkout de desenvolvimento, use `php bin/acbr-php` após `composer install`.
Veja [ajuda de campos, tipos e comandos](docs/ajuda.md) para caminhos,
coleções, API PHP e códigos de saída.

### Modelos e help

    use Acbr\Contract;
    use Acbr\Model\TNFe;
    use Acbr\Model\TProd;

    $produto = (new TProd())->withCProd('TESTE')->withVProd('10.00');
    $nota = TNFe::fromJson($jsonDocumento);

    $campo = Contract::bundled()->help('Det[].Prod.vProd');
    $tipo = Contract::bundled()->help('TProd');
    $campos = Contract::bundled()->fields('TProd');

Os nomes e maiúsculas dos campos seguem exatamente o contrato ACBr. Valores decimais
são **strings**, nunca float: '10.00'. Datas são strings ISO civis, sem conversão
automática de fuso. Campos omitidos continuam omitidos, sem defaults fiscais inventados.
Campos desconhecidos, read-only, enums incorretos e tipos incompatíveis são rejeitados.
Document e modelos são imutáveis; métodos with... retornam novos objetos.

Na raiz do checkout de desenvolvimento, os exemplos e o gerador podem ser executados assim:

    php examples/ajuda.php 'Det[].Prod.vProd'
    php scripts/generate-models.php --check

### Erros e cancelamento

    try {
        $resultado = $emissor->gerarXml($documento, cancelled: fn () => connection_aborted() !== 0);
    } catch (\Acbr\AcbrException $erro) {
        $diagnosticoSeguro = $erro->jsonSerialize();
    }

AcbrException expõe codigo, resultadoDesconhecido e limpezaConfirmada.
Erros de documento usam Acbr\Model\ValidationException; help usa FieldHelpException.
Não grave XML ou FiscalResult::data() em logs: a serialização JSON/debug padrão do
resultado traz somente estado e códigos seguros. Não há repetição automática de operações.
Cancelamento é cooperativo, verificado durante a chamada síncrona. Veja a fila em [async](docs/async.md).
O timeout cobre a comunicação após proc_open, não a verificação prévia de arquivos.
Nenhuma API user-space pode garantir matar um processo bloqueado no kernel;
se o encerramento não for confirmado, o emissor é fechado e o erro sinaliza esse fato.

## Testes

Execute na raiz do checkout de desenvolvimento, após `composer install`:

    composer validate --strict
    composer test
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:offline
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:signing-offline
    ACBR_TEST_RUNTIME=/caminho/runtime composer test:async-native-offline
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:recovery-native-offline

A suíte usa Python 3 somente como worker de teste; o pacote PHP não depende de Python.
Veja [verificação](docs/verification.md) para resultados e limites do ambiente testado.

## A1, CSC e operações fiscais

    use Acbr\CertificateA1;
    use Acbr\Csc;

    $config = new Configuration(
        '11222333000181', 'CE', modelo: 55, ambiente: 2,
        certificado: CertificateA1::fromFile($caminhoPfx, $senhaObtidaDeFonteSegura),
    );
    $emissor = new Emitter($config);
    $assinado = $emissor->assinar($xml);

Para NFC-e, configure modelo 65 e csc: new Csc($idCsc, $tokenCsc).
Dados de A1/CSC seguem somente pelo stdin do processo nativo, nunca pelos argumentos.
JSON/debug de configuração não expõem CNPJ, PFX, senha ou CSC.
Veja [certificados e assinatura](docs/certificates.md).

As operações de rede exigem uma decisão explícita da aplicação do consumidor.
O SDK não conecta só por construir objetos ou instalar o runtime.
Os formatos são:

    $emissor->transmitir($xmlAssinado);
    $emissor->consultar(['chave' => $chave, 'recibo' => $reciboOpcional]);
    $emissor->recuperarXml($xmlOriginalAssinado);
    $emissor->cancelar(['chave' => $chave, 'protocolo' => $protocolo, 'justificativa' => $texto]);
    $emissor->cartaCorrecao(['chave' => $chave, 'correcao' => $texto, 'sequenciaEvento' => 1]);
    $emissor->inutilizar(['ano' => 26, 'serie' => 1, 'numeroInicial' => 1, 'numeroFinal' => 2, 'justificativa' => $texto]);

Omita recibo quando não houver; não envie null. Carta de correção é restrita ao modelo 55.
Identificadores fiscais são strings. Textos respeitam os limites UTF-8 em bytes do motor.

`recuperarXml()` faz uma consulta pelo XML original assinado e devolve o `nfeProc`
montado e validado pelo worker 0.2.1. Sucesso é `XML_RECUPERADO` com cStat100;
não retransmite nem altera `consultar()`. `retornoOriginal()` expõe explicitamente
os campos ACBr disponíveis no frame, sem colocá-los no JSON/debug padrão.
Veja [API, limites e exemplo de recuperação](docs/recuperar-xml.md).

resultado->statusEnvio() exige estado, cStat e identidade/protocolo coerentes antes
de classificar uma operação como confirmada. Resultado incompleto ou contraditório
permanece resultado_incerto. Em erro de transmissão incerta, xmlRecuperacao() dá
acesso explícito ao XML original; ele não aparece no JSON/debug do erro.
**Nunca retransmita automaticamente depois de timeout, conexão interrompida ou resultado incerto.**

## Matriz, limites e homologação

A geração/assinatura/validação real foi testada offline para NF-e 55 e NFC-e 65,
incluindo verificação independente de XMLDSig e QR/CSC. Os testes automatizados
de operações de rede usam mocks ou HTTPS local com dados sintéticos.
Separadamente, um roteiro executado pessoalmente em homologação PE comprovou
emissão, cancelamento, inutilização individual/faixa, consulta e recuperação
integrada `recuperarXml` para NF-e 55, usando as versões históricas descritas em
[verificação](docs/verification.md). A prova do caso exercitado não certifica
todos os cenários ou produção. Ambiente 2 continua sendo uma transmissão fiscal
real quando a aplicação chama uma operação de rede.

O pacote mantém Linux x64/glibc como alvo. PHP 8.2/8.3/8.4 e detalhes de prova
estão em [matriz](docs/platform-matrix.md) e [verificação](docs/verification.md).
DANFE/impressão e A3 não existem no motor fixado. Exigem extensão/substituição
nativa própria; não foram simulados como métodos funcionais.
Windows e ARM não são suportados por este pacote, conforme escopo Linux.

## Origem e licença

LGPL-2.1-or-later. Preserve [LICENSE](LICENSE), [NOTICE](NOTICE), sources/ e licenses/
do runtime ao redistribuir. O motor não foi alterado e permanece como componente separado;
nesta versão o instalador aceita somente a distribuição fixada.
Alterações de motor/contrato precisam de atualização explícita dos pins e da geração/testes.

Âncora do manifesto de publicação upstream:
https://github.com/cosmemilton/acbr-node/tree/7330817e4628b5fb7b49d4c638cdc23da0228ce8
Esse vínculo não atesta o commit de compilação do binário nem um `gitHead` npm.

Contrato: revisão ACBr 48590, SHA-256 e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d.
Não é um pacote oficial do Projeto ACBr.
