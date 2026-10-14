<?php

declare(strict_types=1);

namespace Acbr\Model;

// Generated from the pinned ACBr contract. LGPL-2.1-or-later. Do not edit.
final class TSignature extends \Acbr\Document
{
    public const TYPE = 'TSignature';

    public function getDigestValue(): ?string
    {
        $value = $this->get('DigestValue');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withDigestValue(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['DigestValue' => $value]);
    }

    public function getIdSignature(): ?string
    {
        $value = $this->get('IdSignature');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIdSignature(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IdSignature' => $value]);
    }

    public function getIdSignatureValue(): ?string
    {
        $value = $this->get('IdSignatureValue');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withIdSignatureValue(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['IdSignatureValue' => $value]);
    }

    public function getSignatureValue(): ?string
    {
        $value = $this->get('SignatureValue');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withSignatureValue(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['SignatureValue' => $value]);
    }

    public function getURI(): ?string
    {
        $value = $this->get('URI');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withURI(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['URI' => $value]);
    }

    public function getX509Certificate(): ?string
    {
        $value = $this->get('X509Certificate');
        return $value === null ? null : $value;
    }

    /** @param string $value Validated without scalar coercion. */
    public function withX509Certificate(#[\SensitiveParameter] mixed $value): static
    {
        return $this->with(['X509Certificate' => $value]);
    }
}
