<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

final class VersionCommand extends AbstractCommand
{
    public function execute(): void
    {
        $this->printVersion();
    }
}