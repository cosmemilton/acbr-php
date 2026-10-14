# Arquitetura e próximos marcos

## Camadas atuais

1. Configuration: CNPJ textual, UF, modelo 55/65 e ambiente. Não transforma identificadores em números.
2. Contract: carrega a IR fixada e valida dados/expõe ajuda. A IR contém 204 tipos.
3. Document e Acbr\Model: modelos imutáveis; 124 objetos, 55 enums e 25 coleções gerados deterministicamente.
4. Emitter: API síncrona local gerarXml/validar, contrato e configuração para cada operação, fechamento permanente.
5. NativeRuntime: inventário/checksums, instalação offline, ambiente mínimo e diagnóstico sem executar código nativo.
6. ProcessTransport: processo novo por chamada, argv sem shell, --parent-pid, JSONL via stdin, resultado validado por id/versão/revisão/hash.
7. FiscalResult/AcbrException: acesso explícito a XML/dados, diagnóstico JSON/debug reduzido; falhas de transporte não provocam repetição automática.

Não há aplicação Laravel/Symfony, container de serviços ou mecanismo FFI. O Composer fornece somente autoload PSR-4.
O runtime é distribuído separado do código PHP e não é reconstruído por este pacote.

## Protocolo

A requisição inclui versao, id aleatório, sourceRevision, contractHash, comando, config
e documento ou xmlBase64. A entrada é uma única linha JSON e a pipe é fechada depois do envio.
O worker também encerra após uma operação. Não há pool persistente.

stdout só aceita JSON UTF-8 delimitado por newline. stderr é drenado, somado ao limite,
e descartado sem ser incluído no erro. Uma resposta válida precisa de id, revisão e hash
corretos, sucesso booleano, estado conhecido e formato válido dos campos opcionais.
Duplicidade, truncamento, saída excessiva, erro de processo ou ausência de resposta falham.

O deadline usa relógio monotônico. I/O não bloqueante permite detectar timeout/cancelamento
mesmo com stdin cheio. O encerramento usa somente o handle criado por proc_open,
sem varredura de processos. O protocolo --parent-pid do worker complementa a limpeza
quando o pai desaparece. Não usamos sinais em grupos de processos desconhecidos.

## Modelo de ameaça e limites

- Pacote e diretório do runtime devem pertencer a uma implantação confiável. Os hashes
  não eliminam corrida se um atacante já puder trocar arquivos durante a execução.
- Não são aceitos symlinks, arquivos adicionais nem versões de runtime "compatíveis" por aproximação.
- Nenhuma função user-space garante encerrar um processo travado no kernel. Esse caso
  precisa de supervisão do serviço/SO; é sinalizado como limpeza não confirmada.
- O callback de cancelamento deve ser rápido e não bloqueante. PHP não preempta callbacks.
- Os limites cobrem bytes da requisição e saída total, não todos os gastos de memória
  que a aplicação do consumidor faça antes de chamar o SDK.
- JSON de diagnóstico/debug evita dados fiscais; acesso explícito a data()/xml() devolve
  os dados reais. Não serialize resultados completos nem grave dumps arbitrários em produção.
- SensitiveParameter protege argumentos nas stacks do SDK; chamadas externas e
  mecanismos de observabilidade do consumidor também precisam de política de redaction.

## Marcos seguintes (não concluídos)

1. Certificado A1 e CSC: representação privada, ciclo de vida, pipes e testes sintéticos sem segredos reais.
2. assinar com certificados de teste gerados especificamente para laboratório.
3. consultar/transmitir/cancelar/cartaCorrecao/inutilizar: validação de argumentos,
   resultado incerto, recuperação e nenhuma repetição automática.
4. Testes de rede apenas com endpoints locais simulados e autorização específica;
   homologação real fica separada dos testes de desenvolvimento.
5. Suíte NFC-e modelo 65 dedicada, matriz PHP 8.2/8.3/8.4 e Linux suportados.
6. Publicação/release somente após revisão e autorização; este checkpoint não foi publicado no Packagist.

DANFE, impressão, A3, ARM e Windows não fazem parte do escopo inicial.
