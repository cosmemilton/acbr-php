<?php

declare(strict_types=1);

namespace Acbr\Model;

final class FieldHelpException extends \InvalidArgumentException
{
    /** @param list<string> $candidates */
    public function __construct(
        public readonly string $codigo,
        string $message,
        public readonly array $candidates = [],
    ) {
        parent::__construct($message);
    }
}
