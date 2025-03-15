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
        $this->maxVariableHardUsage = max(array_map(fn(Scope $scope) => $scope->getVariableHardUsage(), $scopes));
        if (count($scopes) === 0) {
            $this->avarageVariableHardUsage = 0;
        } else {
            $this->avarageVariableHardUsage = array_sum(array_map(fn(Scope $scope) => $scope->getVariableHardUsage(), $scopes)) / count($scopes);
        }
    }

    public function format(): string
    {
        $output = [
            'maxVariableHardUsage' => $this->maxVariableHardUsage,
            'avarageVariableHardUsage' => $this->avarageVariableHardUsage,
        ];
        $output['scopes'] = array_map(fn(Scope $scope) => ['name' => $scope->getName(), 'variableHardUsage' => $scope->getVariableHardUsage()], $this->scopes);
        return json_encode($output, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}