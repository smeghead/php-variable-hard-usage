<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

use Smeghead\PhpVariableHardUsage\Parse\Func;
use Smeghead\PhpVariableHardUsage\Parse\VarReference;

final class VariableAnalyzer
{
    private const ASSIGNED_VARIABLE_COEFFICIENT = 2;

    /**
     * @param list<Func> $functions
     */
    public function __construct(
        public readonly string $filename,
        public readonly array $functions
    )
    {
    }

    public function analyze(): AnalysisResult
    {
        return new AnalysisResult($this->filename, array_map(fn($f) => $this->analyzeFunction($f), $this->functions));
    }

    private function analyzeFunction(Func $function): Scope
    {
        $variables = $function->getVariables();
        $variableNames = array_unique(array_map(fn($variable) => $variable->name, $variables));

        $analyzedVars = [];

        foreach ($variableNames as $variableName) {
            // array_values でインデックスを振り直す
            $vars = array_values(array_filter($variables, fn($variable) => $variable->name === $variableName));
            $variableHardUsage = $this->calcVariableHardUsage($vars);
            $analyzedVars[] = new AnalyzedVariable($variableName, $variableHardUsage);
        }

        return new Scope($function->namespace, $function->name, $analyzedVars);
    }

    /**
     * @param list<VarReference> $vars
     */
    private function calcVariableHardUsage(array $vars): int
    {
        $firstLineNumber = $vars[0]->lineNumber;
        return array_sum(array_map(fn(VarReference $var) => ($var->lineNumber - $firstLineNumber) * ($var->assigned ? self::ASSIGNED_VARIABLE_COEFFICIENT : 1), $vars));
    }
}