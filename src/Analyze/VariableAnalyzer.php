<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

use Smeghead\PhpVariableHardUsage\Parse\Func;
use Smeghead\PhpVariableHardUsage\Parse\VarReference;

final class VariableAnalyzer
{
    /**
     * @var list<Func>
     */
    private array $functions;

    /**
     * @param list<Func> $functions
     */
    public function __construct(array $functions)
    {
        $this->functions = $functions;
    }

    public function analyze(): AnalysisResult
    {
        return new AnalysisResult(array_map(fn($f) => $this->analyzeFunction($f), $this->functions));
    }

    private function analyzeFunction(Func $function): Scope
    {
        $variables = $function->getVariables();
        $variableNames = array_unique(array_map(fn($variable) => $variable->name, $variables));

        $analyzedVars = [];

        foreach ($variableNames as $variableName) {
            $vars = array_filter($variables, fn($variable) => $variable->name === $variableName);
            $variableHardUsage = $this->calcVariableHardUsage($vars);
            $analyzedVars[] = new AnalyzedVariable($variableName, $variableHardUsage);
        }

        return new FunctionScope($function->name, $analyzedVars);
    }

    /**
     * @param list<VarReference> $vars
     */
    private function calcVariableHardUsage(array $vars): int
    {
        $lineNumbers = array_map(fn($var) => $var->lineNumber, $vars);
        $avarageLinuNumber = intval(array_sum($lineNumbers) / count($lineNumbers));
        $variableHardUsage = array_sum(array_map(fn($lineNumber) => abs($lineNumber - $avarageLinuNumber), $lineNumbers));
        return $variableHardUsage;
    }
}