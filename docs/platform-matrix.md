# Matriz de plataformas e limites do motor

Referência: motor ACBr Node 0.1.2, commit
[`eea82662eb60ea05b5dd164cf293438a202760a7`](https://github.com/cosmemilton/acbr-node/tree/eea82662eb60ea05b5dd164cf293438a202760a7),
revisão ACBr 48590. Revisado em 14/10/2026.

## Versões PHP executadas

Execuções reais em Debian 13 x86-64/glibc, com três interpretadores distintos.
Não foi usada a opção `config.platform.php` do Composer como substituto de execução.

| PHP CLI | Suítes PHP/processos/instalador | Assinatura nativa sintética NF-e/NFC-e | Geração offline, lint, Composer e modelos |
| --- | --- | --- | --- |
| 8.2.34 | 3.579 checks/assertions aprovados | 106 assertions aprovadas | Aprovados |
| 8.3.35 | 3.579 checks/assertions aprovados | 106 assertions aprovadas | Aprovados |
| 8.4.26 | 3.579 checks/assertions aprovados | 106 assertions aprovadas | Aprovados |

As suítes de cada interpretador incluem transporte (70), modelos/contrato (3.228),
runtime (43), configuração/A1/CSC (59), operações com motor injetado simulado (105)
e fila assíncrona (74). O teste do instalador usou o arquivo oficial fixado;
nenhuma suíte foi pulada. A suíte separada de assinatura usa certificados A1
efêmeros sintéticos, modelos 55 e 65, verificação independente de XMLDSig e QR Code,
sem chamadas SEFAZ. Os 204 modelos foram conferidos por geração determinística.
Hashes de fontes, testes, fixtures, CLI e manifests Composer permaneceram iguais
antes e depois da matriz.

PHP 8.2.34 e 8.3.35 foram compilados de fontes oficiais, conferidas por SHA-256
publicado em [PHP 8.2](https://www.php.net/releases/index.php?json&version=8.2)
e [PHP 8.3](https://www.php.net/releases/index.php?json&version=8.3).
PHP 8.4.26 veio de pacotes oficiais Debian. Os builds 8.2/8.3 incluem DOM/libxml,
XML, SimpleXML e OpenSSL para os testes; os pacotes locais libxml2/libxml2-dev
foram conferidos contra metadados Debian assinados e hashes SHA-256 antes da extração.
Não houve instalação como root, execução de scripts de pacotes nem alteração do sistema.

Esta evidência cobre PHP CLI no ambiente acima. FPM, Apache, outras versões PHP,
outras distribuições e CI remota não foram executados nesta matriz.

## Plataformas

| Ambiente | Situação neste SDK PHP | Evidência e limite |
| --- | --- | --- |
| Linux x86-64, glibc, PHP 64 bits | Plataforma admitida | O pin, manifesto e verificador exigem `linux/x64`. Execuções reais realizadas em Debian 13; o runtime upstream foi construído para Ubuntu 24.04. |
| Outras distribuições Linux/glibc | Compatibilidade precisa ser verificada no destino | Hashes corretos não comprovam compatibilidade do carregador/glibc nem execução do motor. |
| Windows x64 | Não implementado nem validado no SDK PHP | O upstream distribui um motor Windows separado, mas este instalador aceita somente o pin Linux e rejeita Windows. Evidência de testes Node/Windows não é evidência de testes PHP/Windows. |
| Linux ARM64/ARM, macOS ou PHP 32 bits | Não suportado | Não há artefato correspondente na release fixada; o verificador recusa arquitetura/sistema incompatível. |
| Alpine/musl | Não suportado | O binário distribuído depende de glibc. |

Fontes: [manifesto de distribuição](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/release/0.1.2/manifest.json),
[plataformas do upstream](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/README.md),
[verificador PHP](../src/NativeRuntime.php) e [pin Linux](../resources/runtime-release.json).

O suporte Windows exige uma implementação e testes reais do transporte PHP no Windows,
incluindo pipes, timeout, cancelamento, isolamento do ambiente e encerramento do processo;
também exige pin próprio e verificação do inventário/arquivos PE/DLL. Trocar o nome do
executável ou reutilizar hashes Linux não satisfaz esses requisitos. ARM exige antes um
novo build nativo e suas dependências, distribuição verificável e testes no alvo.

## DANFE, impressão e certificado A3

Esses recursos não estão presentes no motor fixado, portanto não são expostos como se
fossem comandos implementados pela API PHP.

- **DANFE/impressão:** o protocolo aceita exatamente `gerarXml`, `validar`, `assinar`,
  `transmitir`, `consultar`, `cancelar`, `cartaCorrecao` e `inutilizar`. Não existe comando
  de PDF, DANFE ou impressão. O build upstream usa `NOGUI` e `NOREPORT`.
- **A3:** o protocolo de certificado recebe somente `pfxBase64` e `senha`; o motor
  exige expressamente `CertTipo = tpcA1`. Não há parâmetros de token/cartão, provedor
  PKCS#11 ou identificação de dispositivo. Um A3 não pode ser habilitado renomeando
  uma configuração A1.
- A existência de tipos de impressão no modelo fiscal, como `TACBrTipoImpressao`,
  descreve campos do XML e não fornece um renderizador de DANFE.

Fontes: [allowlist do protocolo](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/native/NativeProtocol.pas),
[restrição A1 do motor](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/native/NativeMotor.pas),
[opções do build](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/docs/CLI.md)
e [escopo declarado pelo upstream](https://github.com/cosmemilton/acbr-node/blob/eea82662eb60ea05b5dd164cf293438a202760a7/README.md).

Para incluí-los, é necessário desenvolver/revisar o componente apropriado, atualizar
explicitamente o contrato e a distribuição quando necessário e acrescentar testes do
recurso real. Nenhum comando alternativo, biblioteca proprietária ou driver de dispositivo
foi instalado implicitamente por este SDK.

## Homologação fiscal

As verificações locais usam documentos e certificados sintéticos. Não foram feitas
chamadas reais à SEFAZ, testes com certificados de contribuintes nem testes de produção.
Sucesso de assinatura/schema, de transporte simulado ou do motor offline não equivale à
homologação fiscal nem valida o preenchimento tributário da aplicação consumidora.

A evidência de execução por versão PHP fica em [verificação](verification.md).
