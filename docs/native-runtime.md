# Runtime nativo: instalação e verificação

O PHP conversa diretamente com o processo Free Pascal do ACBr por pipes. Node.js, npm, FFI e extensões PHP proprietárias não são necessários para executar este SDK. O arquivo distribuído pelo upstream usa o formato de pacote npm apenas como embalagem.

## Plataforma e pré-requisitos

- PHP 8.2 ou superior, 64 bits, com `proc_open` habilitado.
- Linux x86-64 com glibc. O upstream constrói este runtime para Ubuntu 24.04. Não há suporte a Alpine/musl, ARM, macOS ou Windows neste pacote PHP.
- Extensão `zlib` do PHP para instalar um `.tgz`; desnecessária para copiar um diretório já verificado.
- Espaço para o arquivo de aproximadamente 52 MB, o runtime instalado de aproximadamente 90 MB e uma área temporária do mesmo tamanho durante a instalação.

A verificação de hashes não testa compatibilidade da glibc, disponibilidade do carregador do sistema, comportamento do motor ou homologação fiscal. O diagnóstico retorna `nativeExecutionChecked: false` porque nunca executa o motor nem usa `ldd` sobre ele. Os testes offline do motor são uma verificação separada.

## Versão fixada e cadeia de confiança

`resources/runtime-release.json` fixa:

- Projeto upstream: `https://github.com/cosmemilton/acbr-node`
- Commit que ancora o manifesto de publicação: `5ff66828eb70f942608517f1e11719272b2786b8`
- Publicação: `cosmemilton-acbr-node-linux-x64` versão `0.2.0`
- Revisão SVN ACBr: `48590`
- Contrato: `e5a497cefe8e0e350a30da5cc04e88b35c38a98535893d52f878890260d1346d`
- Arquivo: `cosmemilton-acbr-node-linux-x64-0.2.0.tgz`, exatamente `51633932` bytes
- SHA256 do arquivo: `d2beccbcf31820ebfc49ce0e246b8fe500f5757df458a0985cd09745ea369c45`
- SHA256 do `runtime/manifest.json`: `17f1f7ae68f2c358f7815130301cc9ea83c0919cc0faf185ceb02e9d93f34f29`

O hash e o tamanho do arquivo foram obtidos do manifesto de publicação no commit fixado. O manifesto do runtime foi extraído do arquivo oficial após a confirmação desses valores. Todos os 252 arquivos inventariados foram verificados. O SDK não aceita um manifesto arbitrário apenas porque os hashes dentro dele são consistentes.

Esse commit identifica a cópia versionada do manifesto de publicação. O registro
npm consultado não informou `gitHead`; portanto, ele não é apresentado como prova
do commit que publicou o pacote npm.

A cadeia de confiança começa na cópia confiável deste pacote PHP, incluindo seu código e arquivo de versão fixada. SHA256 detecta divergência; não substitui a confiança inicial no distribuidor. A atualização da versão requer revisão explícita de código, contrato, publicação e hashes, não uma opção `--force` ou um checksum fornecido pela linha de comando.

## Instalação offline

O instalador não faz downloads e não executa scripts do pacote. Obtenha o arquivo fixado por um canal HTTPS confiável e preserve seu nome local:

```sh
curl --fail --location --proto '=https' --tlsv1.2 \
  -o cosmemilton-acbr-node-linux-x64-0.2.0.tgz \
  https://registry.npmjs.org/cosmemilton-acbr-node-linux-x64/-/cosmemilton-acbr-node-linux-x64-0.2.0.tgz

php vendor/bin/acbr-php runtime:install \
  ./cosmemilton-acbr-node-linux-x64-0.2.0.tgz \
  /opt/minha-aplicacao/acbr-runtime-0.2.0
```

Sem o último argumento, o destino é `runtime/linux-x64` dentro do pacote PHP. Em um checkout de desenvolvimento, use `php bin/acbr-php`.

O instalador verifica tamanho e SHA256 antes de abrir o gzip. O leitor restrito de tar rejeita travessia de diretórios, caminhos absolutos, links simbólicos, hard links, dispositivos, entradas repetidas e metadados estendidos. Há limites de tamanho e quantidade de entradas. Somente arquivos de `package/runtime/` são copiados. Licenças e fontes existentes dentro do runtime são preservadas.

A instalação usa uma área temporária privada, verifica novamente o inventário completo e publica o diretório por renomeação no mesmo sistema de arquivos. Um destino existente causa erro; não há substituição automática. Nenhum executável é iniciado durante instalação, verificação ou diagnóstico.

Também é possível copiar um runtime previamente instalado e que corresponda exatamente à publicação fixada:

```sh
php vendor/bin/acbr-php runtime:install \
  /caminho/node_modules/cosmemilton-acbr-node-linux-x64/runtime \
  /opt/minha-aplicacao/acbr-runtime-0.2.0
```

O diretório de origem passa pela mesma verificação de manifesto e dos 252 arquivos. Outras versões, mesmo com contrato fiscal idêntico, são rejeitadas. O destino não pode ser um subdiretório da origem.

## Resolução e diagnóstico

A resolução usa, nesta ordem:

1. Diretório passado explicitamente à API.
2. Variável `ACBR_RUNTIME_DIR`.
3. `runtime/linux-x64` do pacote PHP.

```sh
export ACBR_RUNTIME_DIR=/opt/minha-aplicacao/acbr-runtime-0.2.0
php vendor/bin/acbr-php runtime:verify
php vendor/bin/acbr-php runtime:diagnose
php vendor/bin/acbr-php runtime:release
```

`runtime:verify` confirma a integridade sem executar o motor. `runtime:diagnose` também informa plataforma, versão PHP e disponibilidade de `proc_open` e `zlib`. A saída é JSON; sucesso retorna código 0, falha operacional 1, uso inválido 2. `runtime:release` mostra os dados confiáveis da publicação fixada.

```php
use Acbr\NativeRuntime;

$runtime = NativeRuntime::resolve('/opt/minha-aplicacao/acbr-runtime-0.2.0');
$runtime->directory;       // Raiz canônica verificada; root é um alias.
$runtime->executable;      // Caminho absoluto do acbr-worker verificado.
$runtime->environment();   // Ambiente mínimo para proc_open.
$runtime->manifest;        // Manifesto verificado como array.
```

O ambiente do filho permite apenas `PATH`, `HOME`, `LANG`, `LC_ALL`, `TMPDIR`, `TMP` e `TEMP` do processo PHP. `LD_LIBRARY_PATH`, `OPENSSL_MODULES` e `OPENSSL_CONF` apontam exclusivamente para os arquivos do runtime verificado. Não são herdadas variáveis arbitrárias, credenciais, `LD_PRELOAD` ou `LD_AUDIT`.

## Regras de integridade e operação

- Revisão, contrato, plataforma, manifesto, cada arquivo e inventário exato precisam coincidir.
- O executável precisa estar inventariado e ter permissão de execução.
- Links simbólicos são recusados em qualquer componente do caminho, inclusive nos diretórios ancestrais. Passe um caminho físico, não o link `current` de um deploy.
- Arquivos adicionais, bibliotecas adicionais, diretórios não inventariados e caminhos com `..` são recusados. Grave logs e temporários fora do runtime.
- Mantenha o pacote PHP e o runtime sem permissão de escrita para usuários não confiáveis. A verificação em espaço de usuário não elimina corridas se outra pessoa puder modificar os arquivos entre a verificação e a execução.
- Os arquivos de fonte e licença fazem parte do inventário. Não remova `sources/` nem `licenses/`.
- O runtime exige as bibliotecas básicas do sistema listadas no seu manifesto, incluindo o carregador glibc, `libc.so.6` e `libm.so.6`.

Erros comuns: `RUNTIME_ALTERADO` (hash divergente), `CONTRATO_DIVERGENTE` (revisão/contrato), `RUNTIME_LINK_INVALIDO` (link), `RUNTIME_INCOMPLETO` (arquivo ausente), `RUNTIME_ARQUIVO_EXTRA` (conteúdo adicional), `RUNTIME_NAO_EXECUTAVEL` (permissão) e `RUNTIME_DESTINO_EXISTENTE` (instalação não destrutiva).

## Testes offline

```sh
php tests/runtime-test.php
ACBR_TEST_RUNTIME=/opt/minha-aplicacao/acbr-runtime-0.2.0 \
ACBR_TEST_RUNTIME_ARCHIVE=/caminho/cosmemilton-acbr-node-linux-x64-0.2.0.tgz \
php tests/runtime-test.php
```

Sem fixture oficial, a suíte executa os testes de rejeição e informa o salto da integração. Com as variáveis acima, cobre extração, instalação por cópia, SHA256 alterado, arquivos extras, arquivo ausente, permissão de execução, manifesto divergente, travessia e links de arquivos, raiz e diretórios ancestrais. Esta suíte não executa o motor, não usa certificados e não acessa a SEFAZ.
