<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

final class Command
{
    /**
     * @param list<string> $argv
     * @return int 終了コード
     */
    public function run(array $argv): int
    {
        $factory = new CommandFactory();
        $command = $factory->createCommand($argv);
        return $command->execute();
    }
}