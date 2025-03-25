<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

use Smeghead\PhpVariableHardUsage\Command\CommandInterface;
use Smeghead\PhpVariableHardUsage\Command\HelpCommand;
use Smeghead\PhpVariableHardUsage\Command\SingleCommand;
use Smeghead\PhpVariableHardUsage\Command\ScopesCommand;
use Smeghead\PhpVariableHardUsage\Command\VersionCommand;
use Smeghead\PhpVariableHardUsage\Command\CheckCommand;

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
                fwrite(STDERR, "Usage: php bin/php-variable-hard-usage scopes <path1> [<path2> ...]\n");
                return new HelpCommand();
            }
            // 複数のパスを渡す
            return new ScopesCommand(array_slice($argv, 2));
        }

        if ($arg === 'check') {
            if (count($argv) < 3) {
                fwrite(STDERR, "Usage: php bin/php-variable-hard-usage check [--threshold=<value>] <path1> [<path2> ...]\n");
                return new HelpCommand();
            }
            
            // 残りの引数を解析
            $threshold = null;
            $paths = [];
            
            foreach (array_slice($argv, 2) as $argument) {
                if (preg_match('/^--threshold=(\d+)$/', $argument, $matches)) {
                    $threshold = (int)$matches[1];
                } else {
                    $paths[] = $argument;
                }
            }
            
            if (empty($paths)) {
                fwrite(STDERR, "Usage: php bin/php-variable-hard-usage check [--threshold=<value>] <path1> [<path2> ...]\n");
                return new HelpCommand();
            }
            
            return new CheckCommand($paths, $threshold);
        }

        // 後方互換性のため、コマンドが指定されていない場合は単一ファイルモードとして扱う
        return new SingleCommand($argv[1]);
    }
}