<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse\Exception;

use Exception;

final class ParseFailedException extends Exception
{
    public function __construct(string $message = "Parsing failed", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
