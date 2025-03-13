<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

final class Valiable
{
    private string $name;
    private int $count;
    private int $scopeWidthValue;

    public function __construct(string $name, int $count, int $scopeWidthValue)
    {
        $this->name = $name;
        $this->count = $count;
        $this->scopeWidthValue = $scopeWidthValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getScopeWidthValue(): int
    {
        return $this->scopeWidthValue;
    }
}