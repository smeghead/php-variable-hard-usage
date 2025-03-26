<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Option;

use Smeghead\PhpVariableHardUsage\Command\CheckCommand;
use Smeghead\PhpVariableHardUsage\Command\CommandInterface;
use Smeghead\PhpVariableHardUsage\Command\HelpCommand;
use Smeghead\PhpVariableHardUsage\Command\ScopesCommand;
use Smeghead\PhpVariableHardUsage\Command\SingleCommand;
use Smeghead\PhpVariableHardUsage\Command\VersionCommand;

/**
 * コマンドライン引数を解析し、適切なコマンドと引数を生成するクラス
 */
final class CommandFactory
{
    /** @var list<string> */
    private const array SUB_COMMANDS = [
        'single',
        'scopes',
        'check',
    ];

    /**
     * @param array<string, string|bool> $options オプション
     * @param array<string> $argv コマンドライン引数
     */
    public function __construct(private readonly array $options, private readonly array $argv)
    {
    }

    /**
     * コマンドライン引数を解析し、コマンドと引数を返す
     */
    public function create(): CommandInterface
    {
        // ヘルプと バージョン表示は特別処理
        if (array_key_exists('help', $this->options)) {
            return new HelpCommand();
        }

        if (array_key_exists('version', $this->options)) {
            return new VersionCommand();
        }

        if (count($this->argv) === 0) {
            return new HelpCommand();
        }

        $paths = $this->argv;
        if (in_array($this->argv[0], self::SUB_COMMANDS, true)) {
            $subCommand = $this->argv[0];
            $paths = array_slice($this->argv, 1);
            // コマンドに応じた処理
            switch ($subCommand) {
                case 'single':
                    return $this->parseSingleCommand($paths);
                case 'scopes':
                    return $this->parseScopesCommand($paths);
                case 'check':
                    return $this->parseCheckCommand($paths);
            }
        }
        return new SingleCommand($paths[0]);
    }

    /**
     * 単一ファイルコマンドを解析
     * 
     * @param list<string> $paths
     */
    private function parseSingleCommand(array $paths): CommandInterface
    {
        if (empty($paths)) {
            return new HelpCommand();
        }
        
        return new SingleCommand($paths[0]);
    }

    /**
     * スコープコマンドを解析
     * @param list<string> $paths
     */
    private function parseScopesCommand(array $paths): CommandInterface
    {
        if (empty($paths)) {
            return new HelpCommand();
        }
        
        return new ScopesCommand($paths);
    }

    /**
     * チェックコマンドを解析
     * @param list<string> $paths
     */
    private function parseCheckCommand(array $paths): CommandInterface
    {
        if (empty($paths)) {
            return new HelpCommand();
        }

        $threshold = $this->options['threshold'] ?? null;
        if (isset($threshold)) {
            $threshold = (int) $threshold;
        }
        
        return new CheckCommand($paths, $threshold);
    }        
}
