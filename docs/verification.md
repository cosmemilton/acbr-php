# Verificação da expansão PHP

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

Nenhuma chamada SEFAZ, nenhum certificado real e nenhum teste em produção.
DANFE/impressão e A3 não existem no motor fixado; Windows/ARM permanecem fora do
pacote Linux. O comportamento tributário e a homologação da aplicação consumidora
precisam de validação própria. Nenhuma publicação Packagist ou merge é presumido.
