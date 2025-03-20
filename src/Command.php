<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

final class Command
{
    /**
     * @param list<string> $argv
     */
    public function run(array $argv): void
    {
        $factory = new CommandFactory();
        $command = $factory->createCommand($argv);
        $command->execute();
    }
}