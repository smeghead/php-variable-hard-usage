<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

final class AnalysisResult
{
    public readonly int $maxVariableHardUsage;
    public readonly float $avarageVariableHardUsage;

    /**
     * @param list<Scope> $scopes
     */
    public function __construct(
        public readonly array $scopes
    )
    {
        $maxVariableHardUsage = 0;
        $avarageVariableHardUsage = 0;
        foreach ($scopes as $scope) {
            foreach ($scope->getAnalyzedVariables() as $analyzedVariable) {
                $maxVariableHardUsage = max($maxVariableHardUsage, $analyzedVariable->variableHardUsage);
                $avarageVariableHardUsage += $analyzedVariable->variableHardUsage;
            }
        }
        $this->maxVariableHardUsage = $maxVariableHardUsage;
        $this->avarageVariableHardUsage = $avarageVariableHardUsage / count($scopes);
    }
}