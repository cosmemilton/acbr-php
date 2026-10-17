# Verificação da expansão PHP

## Release SDK 0.1.0-alpha1 e runtime 0.2.1

Esta pré-release do SDK fixa o runtime Linux x64/glibc 0.2.1. A auditoria do
arquivo oficial confirmou **264 arquivos** no novo inventário: os 252 arquivos
de 0.2.0 permanecem byte a byte iguais e foram acrescentados 12 arquivos de
licença em `licenses/common/`. Worker, fontes de recuperação, protocolo, TLS,
bibliotecas e schemas permanecem iguais. Os pins desta release estão em
[runtime nativo](native-runtime.md).

Na preparação desta release, foram executados novamente com o pin **0.2.1**:

- Suíte completa: **3.776 checks/assertions** — processo 70, contrato/modelos
  3.228, runtime 45, configuração 59, operações fiscais 105, recuperação 195 e async 74.
- Geração nativa de XML sintético offline: aprovada.
- Assinatura nativa offline: **106 assertions** aprovadas para NF-e/NFC-e,
  schema, XMLDSig e QR/CSC, com A1 sintético efêmero.
- Supervisão/encerramento do worker nativo: **9 assertions** aprovadas.
- Recuperação nativa offline: **291 assertions**, com `ProcessTransport` real e
  worker de teste compilado das fontes exatas 0.2.1 com `ACBR_NODE_TEST`.
  A1, CA e respostas foram sintéticos; o HTTPS ficou restrito ao loopback.
  O `nfeProc` foi verificado independentemente por XSD, RSA, digest e C14N;
  o inventário oficial de 264 arquivos foi revalidado ao final.
- Composer `validate --strict` e geração determinística dos 204 modelos: aprovados.
- Consumidor Composer separado, pré-tag, instalado por cópia local do checkout:
  CLI `vendor/bin/acbr-php`, instalação/verificação dos 264 arquivos e geração
  nativa de XML sintético offline aprovadas.

Essas execuções não usaram A1 real, SEFAZ ou nova numeração; não alteraram o
truststore do sistema nem o runtime oficial. A compilação de teste permanece
separada do runtime aceito pelo resolvedor do SDK.

Para reproduzir no checkout de desenvolvimento desta release:

```sh
composer install
composer validate --strict
ACBR_TEST_RUNTIME=/caminho/runtime-0.2.1 \
ACBR_TEST_RUNTIME_ARCHIVE=/caminho/cosmemilton-acbr-node-linux-x64-0.2.1.tgz \
composer test
ACBR_RUNTIME_DIR=/caminho/runtime-0.2.1 composer test:offline
ACBR_RUNTIME_DIR=/caminho/runtime-0.2.1 composer test:signing-offline
ACBR_TEST_RUNTIME=/caminho/runtime-0.2.1 composer test:async-native-offline
ACBR_RUNTIME_DIR=/caminho/runtime-0.2.1 composer test:recovery-native-offline
```

Os registros históricos a seguir mantêm sua versão e ambiente. A prova pessoal das
sete etapas e os 291 testes de recuperação com HTTPS sintético pertencem ao
runtime **0.2.0**; não são apresentados como a execução 0.2.1 descrita acima. Os testes
automatizados usam dados sintéticos; o roteiro fiscal pessoal mantém seus dados
e artefatos privados fora do repositório.

O pacote está cadastrado no Packagist. Após a publicação da tag do SDK, a
instalação no consumidor usa `composer require cosmemilton/acbr-php:0.1.0-alpha1`
e a CLI `php vendor/bin/acbr-php`. Os comandos de reprodução abaixo pertencem
a checkouts de desenvolvimento das versões registradas, com `composer install`
e a CLI local `php bin/acbr-php`.

## Recuperação nativa com runtime 0.2.0: 17/10/2026

Execução isolada em WSL Ubuntu 24.04, Linux x86-64/glibc, PHP 8.3.6. Os resultados
desta seção correspondem ao código local de `recuperarXml` e ao runtime 0.2.0;
a matriz de 14/10 abaixo registra a versão anterior.

- Suíte completa: **3.776 checks/assertions** — processo 70, contrato/modelos 3.228,
  runtime 45, configuração 59, operações fiscais 105, recuperação 195 e async 74.
- Runtime oficial 0.2.0: manifesto fixado e **252 arquivos** verificados.
- Motor oficial 0.2.0: **106 assertions de assinatura nativa offline**, NF-e/NFC-e,
  schema, XMLDSig e QR, com A1 sintético efêmero.
- Recuperação nativa offline: **291 assertions**, com `ProcessTransport` real e
  motor compilado das fontes exatas 0.2.0 com `ACBR_NODE_TEST`. O HTTPS ficou
  restrito ao loopback e usou CA, A1 e respostas sintéticas; o runtime oficial
  permaneceu inalterado e foi verificado novamente ao final.
- Composer `validate --strict`, geração determinística de 204 modelos e lint dos
  exemplos aprovados. Revisão independente da API e do roteiro pessoal aprovada.

O teste de recuperação cobre `XML_RECUPERADO`/100 com `nfeProc`, schema, vínculo da
chave/ambiente, digest, RSA/C14N e preservação do XML assinado. Cobre também 217,
protocolo ausente, chave e digest divergentes e assinatura adulterada rejeitada
antes do HTTP. Contagens de requisições verificam ausência de retry automático.
O binário de teste não substitui o runtime fixado: o resolvedor de produção
continua rejeitando runtimes de teste.

Os testes automatizados acima não usaram A1 real, SEFAZ ou nova numeração.
Separadamente, o usuário executou pessoalmente sete etapas em homologação PE,
NF-e 55: emissão, inutilização individual, cancelamento, emissão preservando o
XML assinado sem protocolo, inutilização de faixa, consulta e recuperação
integrada. As seis primeiras etapas usaram o cliente/runtime 0.1.2 e a sexta
montou o `nfeProc` no consumidor. A sétima usou `Emitter::recuperarXml` com o
runtime 0.2.0 e o mesmo XML já autorizado, sem nova numeração ou montagem no
aplicativo; retornou `XML_RECUPERADO`/100. A conferência posterior foi offline:
schema `procNFe_v4.00.xsd`, assinatura/digest, identidade, valor, protocolo da
autorização original e preservação dos bytes XML retornados e das fontes.
Os dados, certificado e artefatos fiscais reais permanecem privados e não fazem
parte deste repositório. Essa prova cobre o caso exercitado, sem certificar
todos os cenários ou produção. O runtime preserva o frame nativo e o `nfeProc`;
não exporta a resposta SOAP original.

```sh
ACBR_TEST_RUNTIME=/caminho/runtime-0.2.0 \
ACBR_TEST_RUNTIME_ARCHIVE=/caminho/cosmemilton-acbr-node-linux-x64-0.2.0.tgz \
composer test
ACBR_RUNTIME_DIR=/caminho/runtime-0.2.0 composer test:signing-offline
ACBR_RUNTIME_DIR=/caminho/runtime-0.2.0 composer test:recovery-native-offline
```

A integração de recuperação sintética exige, além das extensões de teste,
FPC/gcc e fontes completas do runtime. Não altera o truststore do sistema.

## Registro anterior: 14/10/2026

Data: 14/10/2026. Execução exclusivamente em computador cloud Linux x86-64/glibc,
Debian 13. Nenhum arquivo ou recurso do computador do usuário foi usado.

## Matriz executada

PHP 8.2.34, 8.3.35 e 8.4.26, com Composer 2.10.3. PHP 8.2/8.3 foram compilados
de fontes oficiais php.net com SHA-256 conferido; dependências XML oficiais Debian
foram verificadas por assinatura do índice e hashes. Não houve alteração do sistema.

Em cada versão:

- 70 assertions de transporte/processo.
- 3.228 checks de contrato, modelos e help.
- 43 checks de instalador/runtime com o arquivo oficial fixado.
- 59 assertions de configuração/A1/CSC.
- 105 assertions de operações fiscais, incluindo JSONL por subprocesso mock.
- 74 assertions de fila/futures/cancelamento/limpeza.
- Total das seis suítes: **3.579 checks/assertions por versão**.
- Geração nativa de XML sintético NF-e, erros de validação e fechamento do emissor: aprovado.
- **106 assertions nativas offline** de assinatura, NF-e/NFC-e, schema, XMLDSig e QR/CSC.
- **9 assertions de morte do worker real** ao encerrar o wrapper PHP: aprovadas sem enviar JSON ou comando fiscal.
- Composer install/validate --strict, lint PHP e geração determinística de 204 modelos: aprovados.

As mesmas suítes foram repetidas após os ajustes finais de supervisão assíncrona.
Hashes dos arquivos de implementação/teste foram comparados para evitar misturar
resultados de versões diferentes. Não se trata de testes em Windows, ARM ou musl.

Instalação Composer em consumidor separado, com cópia e sem symlink: CLI de
verificação, assinatura nativa, operações mock e fila também aprovadas.

## O que foi testado

### Processo e protocolo

Um worker por chamada, entrada única JSONL, id/revisão/hash, estado e tipos da
resposta, stdout/UTF-8/base64 inválidos, duplicidade, truncamento, saída inesperada,
stderr drenado, limites de bytes, stdin bloqueado, timeout, cancelamento e término
do processo. Falhas de transmissão conservam resultado incerto e recuperação
explícita do XML, sem retry automático.

### Configuração e operações

PFX/P12 limitado a 2 MiB, arquivo local regular, senha UTF-8 sem trim, CSC,
proteção de JSON/debug/serialize/traces e argumentos de callbacks.
Os oito comandos foram exercitados em um Engine mock e em um subprocesso JSONL,
com checagem independente do formato recebido. Isso não chama endpoints fiscais.

Foram cobertos chave, protocolo, recibo, justificativa, sequência de CC-e,
modelo permitido, ano/série/faixa de inutilização, campos desconhecidos e
operações sem certificado. Estados de sucesso só são classificados como
confirmados quando comando, cStat e prova fiscal são coerentes.

### Assinatura e NFC-e reais offline

Testes geram A1 autocertificado efêmero, com CNPJ sintético e senha descartável.
O PFX e a chave privada não são incluídos no repositório ou nos artefatos.

- Geração, assinatura e validação nativas para NF-e55 e NFC-e65.
- RSA-SHA1 de SignedInfo e SHA-1/C14N do conteúdo verificados independentemente.
- QR Code NFC-e v2 e seu hash CSC verificados independentemente.
- Rejeição de XML adulterado, PFX inválido, senha incorreta, CNPJ incompatível,
  certificado expirado e CSC ausente.
- Limite CSC real provado: 256 bytes UTF-8 aceitos; 258/260/512 rejeitados pelo
  worker. A fachada Node conta UTF-16 e pode admitir valores que o nativo rejeita;
  o PHP aplica o limite efetivo de bytes do protocolo.

Aceitar o certificado autocertificado de teste prova o funcionamento criptográfico;
**não prova confiança ICP-Brasil, revogação, homologação ou validade fiscal**.

### Assíncrono

Início realmente não bloqueante, FIFO serial, limite incluindo ativo+pendentes,
cancelamento antes/durante, timeout, callback não cooperativo, morte do filho,
encerramento não confirmado, descarte da fila, Futures, recuperação XML redigida,
backpressure de resultado grande e agendamento com snapshot imutável.
SIGCHLD incompatível é rejeitado; um PID/grupo cuja propriedade foi perdida não
é sinalizado. Não use um reaper externo nos filhos pertencentes à fila.

A prova de morte nativa mantém o outro lado do stdin aberto no pai original; assim,
a morte do worker após SIGKILL do wrapper não pode ser atribuída a EOF. Nenhuma
requisição JSON, certificado ou operação fiscal é enviada nessa prova.

O backend é Linux CLI com pcntl/posix. Depois de matar o wrapper, o worker nativo
fixado dispõe de PR_SET_PDEATHSIG(SIGKILL). Operações arbitrárias/injetadas não ganham
essa garantia automaticamente: callbacks não devem criar trabalho destacado;
descendentes não confirmados deixam a fila bloqueada. Veja [async](async.md).

## Revisão independente

Foram corrigidos e revalidados: classificação indevida de eventos confirmados,
perda de recuperação XML em wrappers de erro, captura de segredos por callbacks
em traces, conversão incorreta de Document na fachada async, mutabilidade dos
argumentos enfileirados, backpressure e supervisão de filhos/SIGCHLD.
As limitações operacionais da fila estão explícitas; nenhuma garantia genérica de
encerramento de processo bloqueado no kernel é feita.

## Proveniência do runtime

- Upstream: cosmemilton/acbr-node, commit eea82662eb60ea05b5dd164cf293438a202760a7.
- Distribuição Linux x64 0.1.2; revisão ACBr 48590.
- Arquivo: 51.569.703 bytes; SHA-256
  e27348cd284a7f7de5276dd6ce90ae7c0bfdb6257685cec115fead36b9592e4e.
- Manifesto SHA-256:
  30cc2a9acf9e7882070a0bba33d3ccd0ff2f406b148f4377e480ccce2f415417.
- Todos os 251 arquivos do inventário foram verificados.
- Fontes/licenças, schemas, serviços, bibliotecas e módulos OpenSSL preservados.
- Nenhum binário nativo foi modificado ou embutido no arquivo de código PHP.

## Reprodução

    composer install
    composer validate --strict
    ACBR_TEST_RUNTIME_ARCHIVE=/caminho/cosmemilton-acbr-node-linux-x64-0.1.2.tgz composer test
    php scripts/generate-models.php --check
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:offline
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:signing-offline
    ACBR_TEST_RUNTIME=/caminho/runtime composer test:async-native-offline

Sem fixture de runtime, a suíte de instalação informa explicitamente o skip de
integração. A suíte async exige CLI Linux com pcntl/posix; os testes criptográficos
exigem OpenSSL/DOM. Essas extensões de teste/async não são dependências obrigatórias
do cliente síncrono que delega criptografia ao executável.

## Limites restantes

Nesse registro de 14/10, nenhuma chamada SEFAZ, nenhum certificado real e nenhum teste em produção.
DANFE/impressão e A3 não existem no motor fixado; Windows/ARM permanecem fora do
pacote Linux. O comportamento tributário e a homologação da aplicação consumidora
precisam de validação própria. O cadastro atual no Packagist e a release
0.1.0-alpha1 são descritos na seção inicial; não alteram a data dessas provas.
