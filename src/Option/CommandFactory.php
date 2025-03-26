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
    /** @var array<string> */
    private array $argv;

    /**
     * @param array<string> $argv コマンドライン引数
     */
    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    /**
     * コマンドライン引数を解析し、コマンドと引数を返す
     */
    public function create(): CommandInterface
    {
        // 引数がない場合はヘルプコマンド
        if (count($this->argv) < 2) {
            return new HelpCommand();
        }

        $command = $this->argv[1];

        // ヘルプと バージョン表示は特別処理
        if ($command === '--help') {
            return new HelpCommand();
        }

        if ($command === '--version') {
            return new VersionCommand();
        }

        // コマンドに応じた処理
        switch ($command) {
            case 'single':
                return $this->parseSingleCommand();
            
            case 'scopes':
                return $this->parseScopesCommand();
            
            case 'check':
                return $this->parseCheckCommand();
            
            default:
                // 後方互換性のため、引数そのものをファイル名として解釈
                return new SingleCommand($command);
        }
    }

    /**
     * 単一ファイルコマンドを解析
     */
    private function parseSingleCommand(): CommandInterface
    {
        $args = array_slice($this->argv, 2);
        
        if (empty($args)) {
            return new HelpCommand();
        }
        
        return new SingleCommand($args[0]);
    }

    /**
     * スコープコマンドを解析
     */
    private function parseScopesCommand(): CommandInterface
    {
        $args = array_slice($this->argv, 2);
        
        if (empty($args)) {
            return new HelpCommand();
        }
        
        return new ScopesCommand($args);
    }

    /**
     * チェックコマンドを解析
     */
    private function parseCheckCommand(): CommandInterface
    {
        $args = array_slice($this->argv, 2);
        
        if (empty($args)) {
            return new HelpCommand();
        }
        
        $parsedArgs = $this->parseArguments($args);
        
        if (empty($parsedArgs->paths)) {
            return new HelpCommand();
        }
        
        $threshold = isset($parsedArgs->options['threshold']) ? intval($parsedArgs->options['threshold']) : null;
        
        return new CheckCommand($parsedArgs->paths, $threshold);
    }
    
    /**
     * コマンドライン引数を解析して、オプションとパスに分離する
     * 
     * @param array<string> $args
     * @return ParsedArguments
     */
    private function parseArguments(array $args): ParsedArguments
    {
        $options = [];
        $paths = [];
        
        $i = 0;
        while ($i < count($args)) {
            $arg = $args[$i];
            
            if ($this->isOptionWithValue($arg, '--threshold', $args, $i)) {
                $options['threshold'] = (int)$args[$i + 1];
                $i += 2;
            } elseif ($this->isOptionWithInlineValue($arg, '--threshold=', $matches)) {
                $options['threshold'] = (int)$matches[1];
                $i++;
            } elseif ($this->isOption($arg)) {
                [$name, $value] = $this->parseOption($arg);
                $options[$name] = $value;
                $i++;
            } else {
                $paths[] = $arg;
                $i++;
            }
        }
        
        return new ParsedArguments($paths, $options);
    }
    
    /**
     * 値を持つオプションかどうかを判定
     * 
     * @param string $arg 現在の引数
     * @param string $optionName オプション名
     * @param array<string> $args 全引数
     * @param int $index 現在の位置
     * @return bool
     */
    private function isOptionWithValue(string $arg, string $optionName, array $args, int $index): bool
    {
        return $arg === $optionName && isset($args[$index + 1]);
    }
    
    /**
     * インライン値を持つオプションかどうかを判定
     * 
     * @param string $arg 現在の引数
     * @param string $prefix オプションのプレフィックス
     * @param null &$matches 正規表現のマッチ結果を格納する変数
     * @return bool
     */
    private function isOptionWithInlineValue(string $arg, string $prefix, &$matches): bool
    {
        return preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', $arg, $matches) === 1;
    }
    
    /**
     * オプションかどうかを判定
     * 
     * @param string $arg 現在の引数
     * @return bool
     */
    private function isOption(string $arg): bool
    {
        return strpos($arg, '--') === 0;
    }
    
    /**
     * オプション文字列をパースして名前と値を取得
     * 
     * @param string $option オプション文字列
     * @return array{0: string, 1: string|bool} [オプション名, オプション値]
     */
    private function parseOption(string $option): array
    {
        $optName = substr($option, 2);
        
        if (strpos($optName, '=') !== false) {
            [$name, $value] = explode('=', $optName, 2);
            return [$name, $value];
        }
        
        return [$optName, true];
    }
}

/**
 * パース済みの引数を表すクラス
 */
final class ParsedArguments
{
    /**
     * @param array<string> $paths パスのリスト
     * @param array<string, string|int|bool|null> $options オプションのマップ
     */
    public function __construct(
        public readonly array $paths,
        public readonly array $options
    ) {
    }
}