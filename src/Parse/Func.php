<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

final class Func
{
    /** @var list<VarReference> */
    private array $variables;

    public function __construct(public readonly string $name)
    {
        $this->variables = [];
    }

    /**
     * @return array<VarReference>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param VarReference $variable
     */
    public function addVariable(VarReference $variable): void
    {
        $this->variables[] = $variable;
    }
}