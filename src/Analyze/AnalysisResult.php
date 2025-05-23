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
        public readonly string $filename,
        public readonly array $scopes
    )
    {
        if (count($scopes) === 0) {
            $this->maxVariableHardUsage = 0;
            $this->avarageVariableHardUsage = 0;
        } else {
            $this->maxVariableHardUsage = max(array_map(fn(Scope $scope) => $scope->getVariableHardUsage(), $scopes));
            $this->avarageVariableHardUsage = array_sum(array_map(fn(Scope $scope) => $scope->getVariableHardUsage(), $scopes)) / count($scopes);
        }
    }

    public function format(): string
    {
        $output = [
            'filename' => $this->filename,
            'maxVariableHardUsage' => $this->maxVariableHardUsage,
            'avarageVariableHardUsage' => $this->avarageVariableHardUsage,
        ];
        $output['scopes'] = array_map(fn(Scope $scope) => [
            'namespace' => $scope->namespace,
            'name' => $scope->name,
            'variableHardUsage' => $scope->getVariableHardUsage()
        ], $this->scopes);
        return json_encode($output, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}