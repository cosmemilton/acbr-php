# ACBr PHP

Cliente PHP independente de Laravel/Symfony para o motor nativo público ACBr NF-e/NFC-e.
**MVP offline em desenvolvimento. Não é uma versão fiscal pronta para produção nem paridade completa com o Node.**

- PHP 8.2+, Composer, sem dependências de framework ou FFI.
- Motor **Linux x64 com glibc**, fixado na distribuição Node 0.1.2; sem Windows, ARM ou Alpine/musl.
- Classes de configuração, documento, resultado e erro; 204 modelos gerados do contrato original.
- gerarXml() e validar() reais via executável, um processo por operação.
- Prazo, cancelamento cooperativo, limites de bytes, pipes não bloqueantes, drenagem de stderr e encerramento do processo pertencente à chamada.
- Help de tipos/campos, validação estrutural e instalador nativo com SHA-256, inventário fechado e rejeição de symlinks.
- Nenhum certificado, senha ou chamada SEFAZ é necessário para o exemplo ou os testes deste MVP.

## Instalação de desenvolvimento

Este pacote ainda não foi publicado no Packagist. Na pasta deste repositório:

    composer install
    php bin/acbr-php runtime:diagnose

Baixe o arquivo fixado; o instalador não faz downloads nem executa scripts externos:

    curl --fail --location --proto '=https' \
      'https://registry.npmjs.org/cosmemilton-acbr-node-linux-x64/-/cosmemilton-acbr-node-linux-x64-0.1.2.tgz' \
      --output /tmp/acbr-linux-x64-0.1.2.tgz
    php bin/acbr-php runtime:install /tmp/acbr-linux-x64-0.1.2.tgz
    php bin/acbr-php runtime:verify
    php bin/acbr-php runtime:diagnose

O instalador compara tamanho e SHA-256 do arquivo com o pin do pacote antes de extrair;
também valida o manifesto fixado e todos os arquivos. Preserva bibliotecas, schemas,
serviços, módulos OpenSSL, fontes e licenças. Não sobrescreve destinos existentes.
A instalação de .tgz precisa de ext-zlib. Veja [runtime nativo](docs/native-runtime.md).

Use ACBR_RUNTIME_DIR=/caminho/absoluto ou passe a pasta ao Emitter para uma instalação externa.
Nunca coloque o runtime em uma pasta gravável por usuários não confiáveis.
Verificação de integridade não substitui permissões de implantação nem protege contra
um atacante que já possa alterar o próprio pacote PHP.

## Uso offline

    require 'vendor/autoload.php';

    use Acbr\Configuration;
    use Acbr\Document;
    use Acbr\Emitter;

    $documento = Document::fromJson(file_get_contents('tests/fixtures/documento-sintetico.json'));
    $emissor = new Emitter(
        new Configuration('11222333000181', 'CE', modelo: 55, ambiente: 2),
        timeoutMs: 10_000,
    );
    $resultado = $emissor->gerarXml($documento);
    if ($resultado->sucesso()) {
        $xml = $resultado->xml(); // acesso explícito: conteúdo fiscal potencialmente sensível
    }
    $emissor->fechar();

A fixture é sintética, sem valor fiscal e nunca deve ser transmitida.
A validação estrutural PHP verifica os tipos do contrato; não substitui regras fiscais.
Um XML gerado sem assinatura pode ser rejeitado pelo schema NF-e que exige assinatura; isso é esperado.

### Modelos e help

    use Acbr\Contract;
    use Acbr\Model\TNFe;
    use Acbr\Model\TProd;

    $produto = (new TProd())->withCProd('TESTE')->withVProd('10.00');
    $nota = TNFe::fromJson(file_get_contents('tests/fixtures/documento-sintetico.json'));

    $campo = Contract::bundled()->help('Det[].Prod.vProd');
    $tipo = Contract::bundled()->help('TProd');
    $campos = Contract::bundled()->fields('TProd');

Os nomes e maiúsculas dos campos seguem exatamente o contrato ACBr. Valores decimais
são **strings**, nunca float: '10.00'. Datas são strings ISO civis, sem conversão
automática de fuso. Campos omitidos continuam omitidos, sem defaults fiscais inventados.
Campos desconhecidos, read-only, enums incorretos e tipos incompatíveis são rejeitados.
Document e modelos são imutáveis; métodos with... retornam novos objetos.

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
Cancelamento é cooperativo, verificado durante a chamada síncrona; não existe fila assíncrona neste MVP.
O timeout cobre a comunicação após proc_open, não a verificação prévia de arquivos.
Nenhuma API user-space pode garantir matar um processo bloqueado no kernel;
se o encerramento não for confirmado, o emissor é fechado e o erro sinaliza esse fato.

## Testes

    composer validate --strict
    composer test
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:offline

A suíte usa Python 3 somente como worker de teste; o pacote PHP não depende de Python.
Veja [verificação](docs/verification.md) para resultados e limites do ambiente testado.

## Escopo ainda pendente

Não implementados neste checkpoint: certificado A1/CSC, assinar, transmitir,
consultar, cancelar, cartaCorrecao, inutilizar, fila assíncrona, homologação SEFAZ,
DANFE/impressão, A3, Windows e ARM. Uma configuração ambiente:1 não significa que produção
foi homologada: as duas operações públicas continuam exclusivamente locais.
Há cobertura real de geração NF-e modelo 55; NFC-e modelo 65 ainda precisa de suíte dedicada.

## Origem e licença

LGPL-2.1-or-later. Preserve [LICENSE](LICENSE), [NOTICE](NOTICE), sources/ e licenses/
do runtime ao redistribuir. O motor não foi alterado e permanece como componente separado;
nesta versão o instalador aceita somente a distribuição fixada.
Alterações de motor/contrato precisam de atualização explícita dos pins e da geração/testes.

Upstream: https://github.com/cosmemilton/acbr-node/tree/eea82662eb60ea05b5dd164cf293438a202760a7

Contrato: revisão ACBr 48590, SHA-256 e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d.
Não é um pacote oficial do Projeto ACBr.
