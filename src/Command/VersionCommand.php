<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

class VersionCommand extends AbstractCommand
{
    public function execute(): int
    {
        $this->printVersion();
        return 0;
    }
}