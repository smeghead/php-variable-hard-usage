<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

final class AnalysisResult
{
    private int $variableCount;
    private int $scopeIssues;
    private int $updateFrequency;
    /** @var list<Valiable> */
    private array $valiables;

    /**
     * @param array<Valiable> $valiables
     */
    public function __construct(
        int $variableCount,
        int $scopeIssues,
        int $updateFrequency,
        array $valiables)
    {
        $this->variableCount = $variableCount;
        $this->scopeIssues = $scopeIssues;
        $this->updateFrequency = $updateFrequency;
        $this->valiables = $valiables;
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

    /**
     * @return array<Valiable>
     */
    public function getValiables(): array
    {
        return $this->valiables;
    }
}