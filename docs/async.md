# Fila assíncrona opcional

`Acbr\Async\AsyncEmitter` executa as mesmas oito operações do `Emitter` e devolve
um `Future` imediatamente. A operação ativa executa em outro processo PHP e abre
um novo worker nativo por chamada. Não é uma chamada síncrona adiada até `await()`.
A fila é FIFO e permite **uma operação ativa por instância**.

## Ambiente suportado

- PHP 8.2 ou superior, **Linux, SAPI CLI**, `ext-pcntl` e `ext-posix` habilitadas.
- `stream_socket_pair`, `proc_open` e as funções de processo não podem estar
  bloqueadas. A ausência do backend opcional produz `ASYNC_INDISPONIVEL`;
  não existe fallback que se apresente como assíncrono e execute sincronicamente.
- O `Emitter` síncrono não depende de `pcntl`/`posix`.
- Use um processo CLI dedicado e não multithread. **Não use a fila dentro de
  PHP-FPM, Apache/mod_php, runtimes multithread, ou depois de abrir conexões de
  banco, clientes de rede ou recursos que não sejam seguros para `fork()`.**
  A verificação de SAPI rejeita servidores web; ela não consegue detectar todos
  os recursos externos herdados pelo processo.
- Em Laravel, Symfony ou outro framework, um job pode usar o `Emitter` síncrono.
  Para esta fila, prefira um CLI dedicado com configuração em memória e sem
  conexões vivas herdadas. Não há dependência de framework ou event loop.
- A fila exige `SIGCHLD` padrão e rejeita antecipadamente `SIG_IGN` ou um
  manipulador personalizado (`ASYNC_SIGNAL_INCOMPATIVEL`). Não mude essa política
  durante seu uso nem recolha seus filhos por fora, inclusive com
  `pcntl_waitpid(-1, ...)`. A fila verifica novamente a política e a propriedade
  do filho antes de sinalizar; se perder a propriedade, não sinaliza o PID antigo
  e informa limpeza não confirmada. Só faz `waitpid` dos filhos que criou.

## Uso com Emitter

```php
use Acbr\Async\AsyncEmitter;
use Acbr\AcbrException;

// $emitter já é um Acbr\Emitter configurado; $xml contém o XML da operação.
$async = new AsyncEmitter($emitter, capacity: 8, timeoutMs: 30000);

try {
    $first = $async->validar($xml);
    $second = $async->gerarXml($documento); // Document, modelo gerado ou array

    // Trabalho PHP independente pode ocorrer aqui enquanto o primeiro executa.
    $validation = $first->await();
    $generated = $second->await();
} catch (AcbrException $error) {
    if ($error->resultadoDesconhecido) {
        // Consulte a situação fiscal. Não retransmita automaticamente.
    }
    if (!$error->limpezaConfirmada) {
        // Interrompa este processo CLI. A fila não aceitará novas operações.
    }
} finally {
    $async->fechar();
}
```

Métodos: `gerarXml`, `validar`, `assinar`, `transmitir`, `consultar`, `cancelar`,
`cartaCorrecao` e `inutilizar`. Os parâmetros fiscais são os mesmos do `Emitter`.
A fachada fornece internamente a função de cancelamento. `gerarXml` transforma
arrays em `Document` imutável ao agendar; os outros argumentos válidos também são
capturados como valores independentes, inclusive quando a origem usa referências
PHP. Assim, alterar os dados da aplicação depois não altera a operação agendada.
A validação desse snapshot pode lançar uma exceção antes de devolver o futuro.

O `Emitter` e a configuração são herdados por `fork()`. Mudanças de estado feitas
pelo filho não atualizam objetos do pai. Engines injetadas para testes devem
respeitar essa separação; contadores na memória do processo pai não refletem as
chamadas do filho. Não execute operações síncronas concorrentes no mesmo emissor.

## Capacidade, progresso e prazos

- `capacity` aceita 1 a 1001 e **inclui a operação ativa**. Com `capacity: 8`, há
  no máximo uma ativa e sete aguardando. Isso difere do `limiteFila` do Node,
  que conta apenas os itens aguardando. Fila cheia lança `FILA_CHEIA` sem executar
  nem aceitar a operação adicional.
- O primeiro item inicia durante o agendamento, após um handshake local curto
  que confirma a criação de um grupo de processos próprio. O agendamento não
  espera o resultado fiscal. A falha desse handshake é limitada a um segundo,
  além da tentativa limitada de recolhimento do filho.
- **Não há loop de eventos oculto no pai.** `Future::await()`, `drain()` e `tick()`
  processam respostas, supervisionam prazos e iniciam o próximo item. Se o pai
  não chamar esses métodos, a operação já iniciada continua, mas o próximo item
  aguardará uma nova chamada de progresso.
- `tick()` não espera por dados. `tick(20)` pode esperar até 20 ms por atividade.
  Mesmo `tick(0)` pode gastar até um segundo ao confirmar limpeza, além do
  handshake para iniciar o próximo item. `Future::state()`, `isComplete()` e
  `result()` também fazem uma passagem de progresso sem espera por dados.
- `drain()` aguarda todos os futuros. **Não lança os erros fiscais individuais**:
  consulte o resultado de cada futuro. `await()` aguarda um futuro e lança seu
  erro, se houver, enquanto também progride a fila.
- `timeoutMs` aceita 1 a 120000 e conta a partir da execução do item; tempo na
  fila não consome esse prazo. O timeout do `Emitter` também continua valendo.
  O probe passado ao transporte observa o prazo mesmo sem o pai fazer polling.
  A supervisão de um callback arbitrário que ignore o probe depende de `tick()`.
- Um resultado concluído no prazo permanece disponível se o pai chamar `await()`
  mais tarde. Respostas maiores que o buffer do socket usam contrapressão:
  o filho PHP pode permanecer aguardando a leitura, com o resultado limitado em
  memória, embora o worker nativo já tenha terminado.
- `maxBytes` limita o envelope de resultado IPC, por padrão 8 MiB. O transporte
  tem seu próprio limite de entrada/saída. A fila não persiste dados em disco;
  limites por item não substituem dimensionar a memória total da aplicação.

Guarde a instância da fila/fachada até os futuros terminarem e chame `fechar()`
explicitamente. O destrutor tenta encerrar, mas não substitui o encerramento
verificável em `finally`. A fila não é durável e não recupera jobs após reinício.
Não serialize nem compartilhe futuros entre processos.

## Futuros e cancelamento

```php
$future = $async->transmitir($xml);

$future->state();       // pending, running, fulfilled ou rejected
$future->isComplete();  // faz uma passagem curta de progresso
$future->cancel();      // solicita cancelamento; false se já terminou
$result = $future->await(); // devolve FiscalResult ou lança AcbrException
```

`result()` não aguarda: lança `RESULTADO_PENDENTE` enquanto não houver resultado.
Cancelar antes de iniciar impede a execução e produz `OPERACAO_CANCELADA`, com
`resultadoDesconhecido=false`. Cancelar uma operação ativa é uma solicitação de
interrupção; **não desfaz um evento já recebido pela SEFAZ**. `Future::cancel()`
não é a operação fiscal `AsyncEmitter::cancelar($dados)`.

Para a execução ativa, a fila primeiro sinaliza o probe. O transporte encerra e
recolhe seu worker. Se o callback não cooperar no prazo de tolerância, a fila
encerra apenas o grupo privado confirmado por seu próprio filho, e espera até
um segundo para verificar o recolhimento. Nunca sinaliza o grupo herdado do pai,
nunca busca processos alheios e nunca reutiliza o worker nativo para outra chamada.
O runtime Linux fixado também configura `PR_SET_PDEATHSIG` para matar seu worker
quando o processo PHP que o iniciou termina. Se o filho PHP já morreu e foi
recolhido, a fila não sinaliza seu antigo grupo: confirma sua ausência ou informa
limpeza incerta. Isso é importante para engines injetadas e callbacks próprios:
processos filhos sem proteção de morte do pai podem sobreviver a uma queda,
mesmo dentro do mesmo grupo. Não se promete encerrar esses descendentes sem
proteção após perder a propriedade do PID. Um init de contêiner que deixe um
órfão zumbi também pode impedir confirmar a limpeza, embora ele já não execute.

`fechar()` rejeita os itens aguardando, solicita a interrupção do ativo e espera a
limpeza limitada. Com a tolerância padrão de 1200 ms, a tentativa de encerramento
do item ativo leva aproximadamente até 2200 ms, além do escalonamento do sistema.
Qualquer limpeza não confirmada fecha permanentemente a fila, rejeita pendências
e expõe `limpezaConfirmada=false`; `fechar()` também lança nesse caso.
Nenhum novo item é iniciado após uma limpeza incerta.

## Segurança fiscal e privacidade

- Não há repetição automática, seja por timeout, erro, queda do filho ou rejeição
  fiscal. `transmitir`, `cancelar`, `cartaCorrecao` e `inutilizar` são tratadas
  conservadoramente como potencialmente efetivas quando interrompidas em execução.
- Rejeições fiscais retornadas continuam sendo `FiscalResult`, com o comando
  preservado para `statusEnvio()` e `resultadoDesconhecido()`; não são convertidas
  em tentativas adicionais. Erros conhecidos antes do envio mantêm sua indicação
  de resultado conhecido.
- Se o transporte devolver XML de recuperação, o IPC o preserva para acesso
  explícito por `$error->xmlRecuperacao()`. Ele não aparece em JSON/debug. Um
  encerramento forçado antes da resposta do filho pode impedir essa recuperação;
  mantenha os identificadores e o XML original em armazenamento próprio seguro.
- O IPC é um socket Unix privado; XML, A1 e CSC não entram em argumentos de
  processo, arquivos temporários ou logs da fila. Mensagens livres, objetos de
  exceção, closures e stack traces do filho não são serializados. Códigos de erro
  conhecidos e flags de resultado/limpeza cruzam o IPC; mensagens são genéricas.
- O acesso explícito a `FiscalResult::data()`, `xml()` e `xmlRecuperacao()` devolve
  dados fiscais sensíveis. Não os registre indiscriminadamente. `fork()` copia o
  espaço de memória do processo; não se promete apagamento físico garantido de
  segredos da memória PHP.

## API de baixo nível

```php
use Acbr\Async\OperationQueue;
use Acbr\FiscalResult;

$queue = new OperationQueue(capacity: 4, timeoutMs: 30000);
try {
    $future = $queue->submit(
        fn(callable $cancelled): FiscalResult => $emitter->validar($xml, $cancelled),
        mayHaveFiscalEffect: false,
    );
    $result = $future->await();
} finally {
    $queue->close();
}
```

Use callbacks confiáveis, que devolvam `FiscalResult` e repassem o probe ao
`Emitter`. O callback executa no filho e não deve imprimir, registrar segredos,
abrir trabalho destacado nem depender de conexões herdadas. Nesta API, capturas
mutáveis são copiadas apenas quando o item inicia via `fork()`; congele os dados
antes de `submit()`, ou prefira `AsyncEmitter`, que faz isso para você.
`mayHaveFiscalEffect` é `true` por padrão; só use `false` quando a operação for
local ou uma consulta sem efeito fiscal. A serialização é por instância, não entre
filas diferentes, processos distintos ou chamadas síncronas paralelas. Coordene
esses casos na aplicação.

## Testes

`php tests/async-test.php` usa apenas mocks locais. Cobre retorno realmente
assíncrono, FIFO/capacidade, cancelamento pendente/ativo, timeout, encerramento
forçado, queda do filho, limpeza incerta, ausência de extensões, backpressure,
snapshots, redaction, XML de recuperação, metadados e recolhimento de um mock
nativo pelo transporte. Não envia operações à SEFAZ, não usa certificado real
e não valida autorização fiscal de ponta a ponta.

`ACBR_TEST_RUNTIME=/caminho/verificado php tests/async-native-offline.php` verifica
separadamente a morte do worker nativo real após a queda deliberada de seu pai
PHP, antes que a fila execute qualquer limpeza. O teste não escreve uma requisição
JSON no worker, não fornece certificado e não executa operação fiscal. Distingue
terminação do worker de eventual zumbi órfão ainda não recolhido pelo init.
