<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

class HelpCommand extends AbstractCommand
{
    public function execute(): int
    {
        $this->printHelp();
        return 0;
    }
}