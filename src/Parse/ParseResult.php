<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

final class ParseResult
{
    /** @var list<Func> */
    private array $functions;

    /**
     * @param array<Func> $functions
     */
    public function __construct(array $functions)
    {
        $this->functions = $functions;
    }

    /**
     * @return array<Valiable>
     */
    public function getfunctions(): array
    {
        return $this->functions;
    }
}