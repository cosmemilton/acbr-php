# Recuperar o XML com protocolo

API do **SDK PHP 0.1.0-alpha1**, com o **runtime nativo 0.2.1** fixado pelo
pacote. Para instalar na aplicação consumidora:

```sh
composer require cosmemilton/acbr-php:0.1.0-alpha1
php vendor/bin/acbr-php runtime:release
```

Instale/verifique o runtime conforme [runtime nativo](native-runtime.md). Em um
checkout de desenvolvimento, `composer install` e `php bin/acbr-php` são os
comandos locais; exemplos e fixtures do repositório são relativos a esse checkout.

```php
$resultado = $emissor->recuperarXml($xmlOriginalAssinado);
```

O método recebe a string UTF-8 da **NFe original assinada**, com até 2 MiB.
Não recebe chave, protocolo ou recibo adicionais. A configuração do emissor deve
conter o A1, CNPJ, UF, modelo e ambiente correspondentes ao documento.

Esta chamada faz uma consulta de rede com o certificado configurado. O worker
nativo 0.2.1 extrai a chave do XML, consulta sua situação e monta o `nfeProc`.
Não gera, assina ou retransmite a nota. `consultar()` permanece com seu contrato
anterior e não passa a montar XML implicitamente.

## Resultado e validação

Sucesso exige `sucesso=true`, `estado=XML_RECUPERADO`, `cStat=100`, chave e
protocolo coerentes, XML completo e `envioIniciado=false`. Esse último campo é o
marcador de **envio fiscal** do protocolo; não significa ausência de consulta de
rede. O transporte rejeita estados contraditórios e não repete a operação.

O motor verifica schema e assinatura do XML original, configuração/identidade,
ambiente, chave e digest do protocolo retornado. A montagem preserva o conteúdo
assinado e a assinatura originais; não substitui nem refaz a assinatura.
Criptografia e composição pertencem ao worker fixado, sem dependência de DOM ou
OpenSSL PHP no uso normal do SDK. Os testes usam essas extensões para uma prova
independente.

```php
try {
    $resultado = $emissor->recuperarXml($xmlOriginalAssinado);
    if ($resultado->sucesso() && $resultado->estado() === 'XML_RECUPERADO') {
        $nfeProc = $resultado->xml(); // conteúdo fiscal privado
    } else {
        $diagnosticoSeguro = $resultado->jsonSerialize();
        $retornoPrivado = $resultado->retornoOriginal();
    }
} finally {
    $emissor->fechar();
}
```

Um status diferente de 100 retorna `sucesso=false`, `estado=CONSULTADO` e
`codigo=RECUPERACAO_NAO_COMPROVADA`, com os campos ACBr disponíveis, sem inventar
protocolo ou XML recuperado. Isso inclui 150, conforme o comportamento do motor
0.2.1. Falhas locais/transporte usam `AcbrException` com códigos e mensagens
seguros. A string original, o A1 e callbacks são argumentos sensíveis.

## Retorno original disponível

`FiscalResult::data()` preserva o frame nativo completo. O novo acesso explícito
`retornoOriginal()` preserva os campos recebidos `cStat`, `xMotivo`, `chave`,
`protocolo`, `recibo`, `xmlBase64` e o XML decodificado quando disponível.
Ausência continua ausência. Não use esses acessos em logs ou JSON público.
`jsonSerialize()` e debug da recuperação expõem somente o diagnóstico seguro.

O retorno é identificado como `origem=frame_nativo`. No runtime 0.2.1, o XML de
sucesso é o `nfeProc` composto pelo próprio motor. O worker não exporta também o
`RetWS`/SOAP bruto dessa consulta: `retornoWebServiceOriginalDisponivel=false`
torna esse limite explícito. O SDK não reconstrói diagnósticos que o motor tenha
descartado e não faz uma segunda consulta para obtê-los.

## Runtime e limites

É necessário instalar a versão Linux x64/glibc **0.2.1** fixada em
`resources/runtime-release.json`, com capability `recuperarXml`. A revisão ACBr
48590, a IR e os 204 modelos permanecem iguais. O manifesto e o arquivo nativo
mudaram; runtimes 0.1.2/0.2.0 não correspondem ao pin desta release. Instale em uma nova pasta
para preservar instalações anteriores.

O worker e as fontes de recuperação 0.2.1 são byte a byte iguais aos de 0.2.0;
a atualização do inventário acrescenta licenças. A prova fiscal pessoal registrada
em [verificação](verification.md) usou 0.2.0 e mantém essa identificação.

Protocolos de 17 dígitos, eventos/protocolos adicionais não suportados e uma
assinatura adicional no protocolo são recusados pelo motor atual. Não remova
assinaturas, eventos ou campos para forçar sucesso. Preserve a NFe de entrada e
o retorno para revisão. Não há retry automático, impressão/DANFE ou A3.

`AsyncEmitter::recuperarXml()` recebe o mesmo XML original e retorna um Future.
O cancelamento assíncrono ocorre pelo Future, conforme as regras da fila Linux
descritas em [async](async.md); o callback opcional é da API síncrona.

Veja o [exemplo de escrita exclusiva](../examples/recuperar-xml.php). Ele recebe
um emissor já configurado pelo chamador, não lê segredos por conta própria e
preserva o arquivo de entrada. O teste fiscal pessoal é uma etapa separada dos
testes sintéticos offline; disponibilizar a API não comprova homologação real.
No consumidor, adapte esse exemplo a caminhos privados da sua aplicação para o
XML assinado de entrada e o novo arquivo de saída; não use caminhos de fixtures
de desenvolvimento como se fossem documentos fiscais do usuário.
