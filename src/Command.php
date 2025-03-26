<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

use Smeghead\PhpVariableHardUsage\Option\CommandFactory;

final class Command
{
    /**
     * @param list<string> $argv
     * @return int 終了コード
     */
    public function run(array $argv): int
    {
        $factory = new CommandFactory($argv);
        $command = $factory->create();
        return $command->execute();
    }
}