<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Parse;

final class VarReference
{
    private string $name;
    private int $lineNumber;
    private bool $updated;

    public function __construct(string $name, int $lineNumber, bool $updated = false)
    {
        $this->name = $name;
        $this->lineNumber = $lineNumber;
        $this->updated = $updated;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function isUpdated(): bool
    {
        return $this->updated;
    }
}
