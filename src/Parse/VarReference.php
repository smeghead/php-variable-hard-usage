<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

final class VarReference
{
    public function __construct(
        public readonly string $name,
        public readonly int $lineNumber,
        public readonly bool $assigned = false
    )
    {
    }
}
