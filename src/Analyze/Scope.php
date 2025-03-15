<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

interface Scope
{
    public function getName(): string;

    /**
     * @return list<AnalyzedVariable>
     */
    public function getAnalyzedVariables(): array;
}