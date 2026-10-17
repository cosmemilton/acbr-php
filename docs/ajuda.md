# Ajuda de campos e tipos

A CLI consulta os metadados do contrato incluído no pacote PHP. `describe`,
`ajuda`, `--help` e `--version` funcionam sem runtime instalado, certificado
ou acesso à rede. Esses comandos não iniciam operações fiscais.

## Instalação e versão

No consumidor Composer da versão **0.1.0-alpha2**, execute na raiz da aplicação:

```sh
composer require cosmemilton/acbr-php:0.1.0-alpha2
php vendor/bin/acbr-php --version
php vendor/bin/acbr-php --help
php vendor/bin/acbr-php describe --help
```

`--version` informa a versão do pacote SDK PHP instalado pelo Composer.
O prefixo `v` de uma versão numérica, como `v0.1.0-alpha2`, é omitido;
nomes de versões de desenvolvimento são preservados. A versão do SDK é
independente do runtime nativo fixado em **0.2.1**. No checkout desta
release, a saída é `acbr-php 0.1.0-alpha2`; após `composer install`, use
`php bin/acbr-php` no lugar de
`php vendor/bin/acbr-php`. Não são necessários arquivos da pasta `examples`
para consultar a ajuda no consumidor.

## Consulta em texto ou JSON

```sh
php vendor/bin/acbr-php describe Ide.tpNF
php vendor/bin/acbr-php describe TTipoNFe
php vendor/bin/acbr-php describe TTipoNFe --json
php vendor/bin/acbr-php describe --json 'Det[].Prod.vProd'
php vendor/bin/acbr-php ajuda Ide.tpNF
```

`describe TARGET` apresenta texto em português. O alvo pode ser um caminho
de campo ou um nome de tipo. Sem alvo, consulta `TNFe`.
`describe TARGET --json` apresenta um objeto estruturado; `--json` também
pode preceder o alvo. `ajuda TARGET` oferece diretamente esse formato JSON,
com o mesmo alvo padrão, e não aceita a opção `--json`.

A descrição de `Ide.tpNF` identifica o enum `TTipoNFe`, seus valores
`tnEntrada` e `tnSaida` e o padrão declarado `tnSaida`. A descrição de
`TTipoNFe` contém os valores do enum, mas não o padrão do campo. O padrão
é um metadado do campo; consultar a ajuda não o insere no documento.

O JSON reúne o resultado de `Contract::help()` e `sdkVersion`, com a versão
do código que atendeu à consulta. Inclui revisão e hashes do contrato,
caminho canônico, tipo Pascal, categoria, tipo de entrada e indicador de
somente leitura. Valores de enum, membros de objetos, tipo de item, limites
e padrão declarado aparecem quando disponíveis no contrato. Exemplo para
processar ou salvar esses metadados:

```sh
php vendor/bin/acbr-php describe Ide.tpNF --json > campo.json
```

## Caminhos e coleções

```sh
php vendor/bin/acbr-php describe TNFe.Ide.tpNF
php vendor/bin/acbr-php describe TProd
php vendor/bin/acbr-php describe Det
php vendor/bin/acbr-php describe 'Det[].Prod.vProd'
php vendor/bin/acbr-php describe 'Det[0].Prod.vProd'
```

Nomes e maiúsculas seguem o contrato ACBr. O prefixo `TNFe.` é opcional
para caminhos de campos. Um nome curto só é resolvido quando identifica
um único campo; em caso de ambiguidade, informe o caminho completo.

`Det` descreve a coleção. Para acessar um campo de seus elementos, use
`[]` ou um índice numérico; o resultado normaliza os índices para `[]`.
Tipos de objeto descrevem seus membros, listas indicam o tipo dos elementos
e enums enumeram as strings aceitas. `inputType` descreve a entrada JSON:
por exemplo, decimais usam strings, como `'1.99'`.

A ajuda não determina obrigatoriedade, alíquotas, enquadramento tributário
ou códigos fiscais. A validação estrutural, a validação de schema e as
operações fiscais continuam sendo etapas próprias da aplicação.

## API PHP preservada

```php
require 'vendor/autoload.php';

use Acbr\Contract;

$contrato = Contract::bundled();
$campo = $contrato->help('Ide.tpNF');
$tipo = $contrato->help('TTipoNFe');
$campos = $contrato->fields('TProd');
$texto = $contrato->formatHelp('Det[].Prod.vProd');
```

`Contract::help()` aceita campos e tipos; `Contract::fields()` lista os
descritores de um tipo de objeto; `Contract::formatHelp()` fornece texto.
Essas APIs mantêm seus retornos anteriores. `sdkVersion` pertence ao
envelope da CLI, sem alterar o retorno de `Contract::help()`.

O padrão de consulta segue a ajuda dos outros clientes ACBr, usando o mesmo
contrato. O PHP conserva seus nomes e tipos de retorno; não acrescenta
funções Python como `describe_field` ou `describe_type`.

## Ajuda de comandos e compatibilidade

```sh
php vendor/bin/acbr-php ajuda --help
php vendor/bin/acbr-php runtime:release --help
php vendor/bin/acbr-php runtime:install --help
php vendor/bin/acbr-php runtime:verify --help
php vendor/bin/acbr-php runtime:diagnose --help
```

`-h` também solicita ajuda. `help describe`, por exemplo, mostra a ajuda
específica de `describe`. Os comandos `runtime:release`, `runtime:install`,
`runtime:verify` e `runtime:diagnose` conservam suas saídas JSON e argumentos
anteriores. Eles não precisam de `--json`. Consulte as instruções de
[instalação no README](../README.md) e de [integridade do runtime](native-runtime.md).

| Consulta | Sucesso | Erro de consulta |
| --- | --- | --- |
| `describe TARGET` | Texto em stdout, saída 0 | Mensagem em stderr, saída 1; stdout vazio |
| `describe TARGET --json` | Objeto JSON em stdout, saída 0 | Diagnóstico JSON em stdout, saída 1 |
| `ajuda TARGET` | Objeto JSON em stdout, saída 0 | Diagnóstico JSON em stdout, saída 1 |
| `--help`, `-h` ou `--version` | Texto em stdout, saída 0 | Não consultam o motor nativo |

Nas consultas `describe` e `ajuda`, uma opção desconhecida, alvo excedente
ou outra sintaxe inválida gera saída 2 e mensagem em stderr. Um alvo válido
na sintaxe que não existe no contrato gera saída 1. Entre os diagnósticos
de consulta estão `CAMPO_INEXISTENTE`,
`CAMPO_AMBIGUO` e `COLECAO_REQUER_INDICE`. Esses códigos e mensagens descrevem
a consulta de metadados, sem incluir XML ou credenciais.
