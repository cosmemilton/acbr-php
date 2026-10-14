# Verificação do checkpoint MVP

Data: 14/10/2026. Ambiente: computador cloud, Debian 13 x86-64 com glibc,
PHP 8.4.26 e Composer 2.10.3. Nenhum arquivo do computador do usuário foi utilizado.

## Resultados

- Composer install e validate --strict: aprovados; pacote sem dependências PHP de terceiros.
- Transporte: 65 assertions aprovadas.
- Contrato/modelos/help: 3.228 checks aprovados.
- Runtime/instalação: 43 checks aprovados usando o .tgz oficial fixado.
- Total dessas três suítes: 3.336 checks/assertions.
- Geração determinística: 204 modelos verificados por generate-models.php --check.
- Lint PHP em src, scripts, tests e examples: aprovado.
- Integração nativa real: geração de NF-e sintética com UTF-8 e decimais exatos,
  rejeição de XML sem assinatura e de XML malformado, e fechamento do emissor: aprovada.
- Pacote instalado em consumidor Composer separado com cópia (sem symlink):
  autoload, CLI de verificação e suíte nativa offline aprovados.
- Revisão independente de transporte/instalador: nenhum achado crítico/alto;
  dois achados identificados foram corrigidos e revalidados.

## Cobertura de falhas

Transporte: resposta dividida, stderr drenado, bytes excessivos, id/contrato incorretos,
JSON/UTF-8/base64 inválidos, estado/tipos inválidos, duplicidade, resposta parcial,
saída extra, ausência de resposta, saída não zero, timeout, cancelamento antes/durante,
stdin bloqueado com 2 MiB, encerramento do processo e envio simulado com resultado incerto.

Modelos: todos os tipos e propriedades gerados, objetos/listas, imutabilidade,
decimais em string, datas civis inválidas, enum, campos desconhecidos/read-only,
help por caminho, distinção JSON {} / [], e diagnóstico sem os valores de entrada.

Privacidade: 12 cenários de trace de modelos e regressão de transporte usando
zend.exception_ignore_args=0. Argumentos sensíveis são mascarados. Erros nativos
json_decode/json_encode são convertidos sem reter a exceção original que carregaria argumentos.

Instalador: pin de versão, tamanho/SHA-256 do arquivo, manifesto, todos os 251 arquivos,
alteração de bytes, inventário extra, ausência de arquivos, permissões, symlinks de arquivo
e diretório, travessia e entradas tar inválidas, destino existente e cópia dentro da origem.
O teste de ambiente verifica separadamente a ausência de LD_PRELOAD e de credenciais.

## Proveniência do runtime usado no resultado final

Arquivo oficial:
https://registry.npmjs.org/cosmemilton-acbr-node-linux-x64/-/cosmemilton-acbr-node-linux-x64-0.1.2.tgz

Tamanho: 51.569.703 bytes.

SHA-256:
e27348cd284a7f7de5276dd6ce90ae7c0bfdb6257685cec115fead36b9592e4e

Manifesto SHA-256:
30cc2a9acf9e7882070a0bba33d3ccd0ff2f406b148f4377e480ccce2f415417

Origem do pin:
https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/release/0.1.2/manifest.json

Uma cópia anterior do runtime foi usada em um teste exploratório, mas tinha dois hashes
de arquivos TypeScript diferentes. Não foi incluída no pacote nem usada para aceitar o
resultado final. O resultado final foi repetido com o arquivo oficial acima.

## Comandos reproduzíveis

    composer install
    composer validate --strict
    ACBR_TEST_RUNTIME_ARCHIVE=/caminho/cosmemilton-acbr-node-linux-x64-0.1.2.tgz composer test
    php scripts/generate-models.php --check
    ACBR_RUNTIME_DIR=/caminho/runtime composer test:offline
    php bin/acbr-php runtime:diagnose /caminho/runtime

Sem o arquivo ou diretório de integração, a suíte de runtime executa os checks de guarda
e informa explicitamente que a integração foi pulada.

## Não verificado / não concluído

- PHP 8.2/8.3 em execução real, outras distribuições Linux e NFC-e modelo 65 dedicada.
- Certificados, assinatura, CSC, transmissão, consulta e eventos SEFAZ.
- Nenhuma chamada SEFAZ, nenhum teste de produção/homologação fiscal e nenhuma publicação Packagist.
- Não é evidência de homologação fiscal nem de conformidade tributária da aplicação do consumidor.
