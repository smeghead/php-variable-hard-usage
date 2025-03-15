<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

final class FunctionScope implements Scope
{
    /** @var list<AnalyzedVariable> */
    private array $analyzedVariables;

    /**
     * @param list<AnalyzedVariable> $analyzedVariables
     */
    public function __construct(private string $name, array $analyzedVariables)
    {
        $this->analyzedVariables = $analyzedVariables;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return list<AnalyzedVariable>
     */
    public function getAnalyzedVariables(): array
    {
        return $this->analyzedVariables;
    }

    public function getVariableHardUsage(): int
    {
        return array_sum(array_map(fn(AnalyzedVariable $variable) => $variable->variableHardUsage, $this->analyzedVariables));
    }
}