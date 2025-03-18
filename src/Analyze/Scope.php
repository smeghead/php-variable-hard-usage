<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

final class Scope
{
    /** @var list<AnalyzedVariable> */
    private array $analyzedVariables;

    /**
     * @param list<AnalyzedVariable> $analyzedVariables
     */
    public function __construct(
        public readonly ?string $namespace,
        public readonly string $name,
        array $analyzedVariables
    )
    {
        $this->analyzedVariables = $analyzedVariables;
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