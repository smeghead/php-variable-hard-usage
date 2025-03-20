<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

use Smeghead\PhpVariableHardUsage\Command\CommandInterface;
use Smeghead\PhpVariableHardUsage\Command\HelpCommand;
use Smeghead\PhpVariableHardUsage\Command\SingleCommand;
use Smeghead\PhpVariableHardUsage\Command\ScopesCommand;
use Smeghead\PhpVariableHardUsage\Command\VersionCommand;

final class CommandFactory
{
    /**
     * @param list<string> $argv
     */
    public function createCommand(array $argv): CommandInterface
    {
        if (count($argv) < 2) {
            return new HelpCommand();
        }

        $arg = $argv[1];

        if ($arg === '--help') {
            return new HelpCommand();
        }

        if ($arg === '--version') {
            return new VersionCommand();
        }

        if ($arg === 'single') {
            if (count($argv) < 3) {
                fwrite(STDERR, "Usage: php bin/php-variable-hard-usage single <file>\n");
                return new HelpCommand();
            }
            return new SingleCommand($argv[2]);
        }

        if ($arg === 'scopes') {
            if (count($argv) < 3) {
                fwrite(STDERR, "Usage: php bin/php-variable-hard-usage scopes <directory>\n");
                return new HelpCommand();
            }
            return new ScopesCommand($argv[2]);
        }

        // 後方互換性のため、コマンドが指定されていない場合は単一ファイルモードとして扱う
        return new SingleCommand($argv[1]);
    }
}