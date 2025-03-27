<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

use Smeghead\PhpVariableHardUsage\Option\CommandFactory;

final class EntryPoint
{
    /**
     * @param array<string, string|bool> $options
     * @param list<string> $argv
     * @return int 終了コード
     */
    public function run(array $options, array $argv): int
    {
        $factory = new CommandFactory($options, $argv);
        $command = $factory->create();
        return $command->execute();
    }
}