<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

final class AnalysisResult
{
    private int $variableCount;
    private int $scopeIssues;
    private int $updateFrequency;

    public function __construct(int $variableCount, int $scopeIssues, int $updateFrequency)
    {
        $this->variableCount = $variableCount;
        $this->scopeIssues = $scopeIssues;
        $this->updateFrequency = $updateFrequency;
    }

    public function getVariableCount(): int
    {
        return $this->variableCount;
    }

    public function getScopeIssues(): int
    {
        return $this->scopeIssues;
    }

    public function getUpdateFrequency(): int
    {
        return $this->updateFrequency;
    }
}