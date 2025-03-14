<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

final class Func
{
    private string $name;
    /** @var list<VarReference> */
    private array $variables;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array<VarReference> $variables
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