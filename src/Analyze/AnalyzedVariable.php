<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Analyze;

final class AnalyzedVariable
{
    public function __construct(
        public readonly string $name,
        public readonly int $variableHardUsage
    )
    {
    }
}