<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

final class ParseResult
{
    /**
     * @param array<Func> $functions
     */
    public function __construct(public readonly array $functions)
    {
    }
}