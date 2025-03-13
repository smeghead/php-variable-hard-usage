<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core\Exception;

use Exception;

final class ParseFailedException extends Exception
{
    public function __construct($message = "Parsing failed", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
