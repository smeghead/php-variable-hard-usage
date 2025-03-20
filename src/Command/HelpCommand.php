<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

final class HelpCommand extends AbstractCommand
{
    public function execute(): void
    {
        $this->printHelp();
    }
}