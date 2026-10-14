# Alterações

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
impressão. Windows/ARM não foram adicionados ao pacote Linux. Não houve publicação
Packagist ou criação de release.

## Checkpoint inicial — 14/10/2026

Composer sem framework, 204 modelos/help derivados da IR, geração/validação offline,
transporte com bounds e runtime Linux verificado integralmente por SHA-256.
