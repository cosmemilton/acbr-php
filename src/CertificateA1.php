<?php
declare(strict_types=1);
namespace Acbr;

/** In-memory A1 input. Cryptographic validity is checked by the native worker. */
final readonly class CertificateA1 implements \JsonSerializable
{
    public const MAX_BYTES = 2097152;

    private function __construct(#[\SensitiveParameter] private string $pfx, #[\SensitiveParameter] private string $password) {}

    public static function fromData(#[\SensitiveParameter] string $pfx, #[\SensitiveParameter] string $password): self
    {
        if ($pfx === '') { throw new AcbrException('CERTIFICADO_INVALIDO', 'Informe os bytes de um certificado A1 PFX/P12.'); }
        if (strlen($pfx) > self::MAX_BYTES) { throw new AcbrException('CERTIFICADO_EXCESSIVO', 'Certificado excede o limite de 2 MiB.'); }
        if (preg_match('//u', $password) !== 1) { throw new AcbrException('CERTIFICADO_INVALIDO', 'A senha do certificado deve ser texto UTF-8.'); }
        return new self($pfx, $password);
    }

    /** Reads a bounded local regular file once; never resolves URL/stream wrappers. */
    public static function fromFile(#[\SensitiveParameter] string $path, #[\SensitiveParameter] string $password): self
    {
        if ($path === '' || str_contains($path, "\0") || str_contains($path, '://') || !@is_file($path)) {
            throw new AcbrException('CERTIFICADO_AUSENTE', 'Não foi possível ler um arquivo local de certificado A1.');
        }
        $file = @fopen($path, 'rb');
        if ($file === false) { throw new AcbrException('CERTIFICADO_AUSENTE', 'Não foi possível ler o certificado A1.'); }
        try {
            $stat = fstat($file);
            if ($stat === false || ($stat['mode'] & 0170000) !== 0100000) { throw new AcbrException('CERTIFICADO_AUSENTE', 'O certificado A1 deve ser um arquivo regular local.'); }
            if ($stat['size'] > self::MAX_BYTES) { throw new AcbrException('CERTIFICADO_EXCESSIVO', 'Certificado excede o limite de 2 MiB.'); }
            $pfx = @stream_get_contents($file, self::MAX_BYTES + 1);
            if ($pfx === false) { throw new AcbrException('CERTIFICADO_AUSENTE', 'Não foi possível ler o certificado A1.'); }
            return self::fromData($pfx, $password);
        } finally { fclose($file); }
    }

    /** @internal Explicit secret-bearing payload for the worker's stdin only. Never log. */
    public function toWire(): array { return ['pfxBase64'=>base64_encode($this->pfx), 'senha'=>$this->password]; }
    public function jsonSerialize(): array { return ['tipo'=>'A1', 'configurado'=>true]; }
    public function __debugInfo(): array { return $this->jsonSerialize(); }
    public function __serialize(): array { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Não serialize certificados. Use somente o envio explícito ao motor.'); }
    public function __unserialize(#[\SensitiveParameter] array $data): void { throw new AcbrException('SEGREDO_NAO_SERIALIZAVEL', 'Reconstrua o certificado com fromData ou fromFile.'); }
}
