<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

final class FunctionScope implements Scope
{
    private array $analyzedVariables;

    public function __construct(private string $name, array $analyzedVariables)
    {
        $this->analyzedVariables = $analyzedVariables;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return list<AlalyzedVariable>
     */
    public function getAnalyzedVariables(): array
    {
        return $this->analyzedVariables;
    }
}