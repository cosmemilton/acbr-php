# Alterações

## 0.1.0-alpha1 - 17/10/2026

- Primeira pré-release versionada do SDK PHP, disponível pelo pacote Composer
  `cosmemilton/acbr-php`; instalação do consumidor com versão explícita e CLI
  `vendor/bin/acbr-php`, separada do checkout de desenvolvimento.
- Runtime Linux x64/glibc 0.2.1 fixado por arquivo, manifesto e inventário.
  A versão do SDK é independente da versão do motor nativo. A revisão ACBr
  48590, a IR e os 204 modelos permanecem iguais.
- Inventário nativo com 264 arquivos: preserva os 252 arquivos anteriores e
  acrescenta 12 arquivos de licença; worker e semântica de recuperação inalterados.
  O SOAP/RetWS bruto da recuperação continua indisponível no frame do motor.
- Nove comandos, incluindo `Emitter::recuperarXml()` e a fachada assíncrona,
  com recuperação/composição nativa e sem retransmissão ou retry automático.
- Documentação de instalação, recuperação, integridade e limites da pré-release.
  As provas históricas com runtimes 0.1.2/0.2.0 mantêm suas versões e resultados;
  não são apresentadas como novas execuções com 0.2.1.

## Em desenvolvimento - recuperação XML - 17/10/2026

- `Emitter::recuperarXml()` e sua fachada assíncrona recebem a NFe original
  assinada e usam o comando nativo 0.2.0 para consultar e devolver `nfeProc`.
- Sucesso restrito a XML_RECUPERADO/cStat100, com validação nativa de identidade,
  ambiente, protocolo, digest e preservação da assinatura. `consultar()` mantém
  o comportamento anterior; não há retransmissão ou retry automático.
- Runtime Linux x64/glibc 0.2.0 fixado por arquivo, manifesto e inventário completo.
  Revisão ACBr, IR e modelos permanecem iguais; instalações 0.1.2 são preservadas
  em sua própria pasta e não são aceitas pelo novo pin.
- Acesso explícito `FiscalResult::retornoOriginal()` preserva os campos ACBr
  disponíveis no frame. O SOAP/RetWS bruto da recuperação não é exportado pelo
  motor atual e não é apresentado como disponível.
- Testes sintéticos positivos/negativos e documentação da API. Em roteiro
  pessoal separado, a recuperação integrada foi comprovada em homologação PE,
  NF-e 55, com XML_RECUPERADO/100 e prova offline de schema, assinatura e protocolo.
  Dados e artefatos fiscais permanecem privados; essa prova não certifica todos
  os cenários ou produção. Esse registro antecede a release 0.1.0-alpha1.

## Em desenvolvimento — 14/10/2026

### Expansão do cliente PHP

- A1 PFX/P12 em memória/arquivo local e CSC com serialização privada para stdin.
- Oito comandos do contrato nativo: gerarXml, validar, assinar, transmitir, consultar,
  cancelar, cartaCorrecao e inutilizar.
- Limites e formatos de parâmetros conforme o protocolo real do motor fixado.
- Engine injetável para testes locais, com NativeEngine como implementação padrão.
- Resultado fiscal conservador por comando, cStat, chave e protocolo; estados
  incompletos não autorizam retransmissão.
- Recuperação explícita do XML original em erros de transmissão incerta.
- Fila assíncrona Linux CLI opcional, Futures e AsyncEmitter, com execução serial,
  capacidade limitada, cancelamento, deadlines, IPC limitado e limpeza de processos.
- Snapshot dos parâmetros no agendamento e proteção de argumentos/callbacks nos traces.
- NFC-e65: teste real offline de XML, assinatura, schema, QR Code e CSC.
- Certificados de teste efêmeros, verificação independente de assinatura/digest e
  rejeição de credenciais/XML inválidos.
- Matriz executada em PHP 8.2.34, 8.3.35 e 8.4.26 sobre Linux x64/glibc.

### Limites mantidos

Nenhum teste chama a SEFAZ nem usa certificado de contribuinte. APIs de rede testadas
por mock não constituem homologação fiscal. O motor fixado continua A1 e sem DANFE/
impressão. Windows/ARM não foram adicionados ao pacote Linux. À data de 14/10,
não havia release versionada do SDK; esse registro antecede 0.1.0-alpha1.

## Checkpoint inicial — 14/10/2026

Composer sem framework, 204 modelos/help derivados da IR, geração/validação offline,
transporte com bounds e runtime Linux verificado integralmente por SHA-256.
