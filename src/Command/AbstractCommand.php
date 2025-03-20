<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

abstract class AbstractCommand implements CommandInterface
{
    private const VERSION = '0.0.4';

    protected function printVersion(): void
    {
        echo "PHP Variable Hard Usage Analyzer, version " . self::VERSION . "\n";
    }

    protected function printHelp(): void
    {
        echo "Usage: php bin/php-variable-hard-usage [command] [options]\n";
        echo "Commands:\n";
        echo "  single <file>                  Analyze a single file\n";
        echo "  scopes <path1> [<path2> ...]  Analyze PHP files in directories or specific files\n";
        echo "Options:\n";
        echo "  --help                         Display help information\n";
        echo "  --version                      Show the version of the tool\n";
    }
}