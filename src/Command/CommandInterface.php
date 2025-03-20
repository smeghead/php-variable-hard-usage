<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

interface CommandInterface
{
    public function execute(): void;
}