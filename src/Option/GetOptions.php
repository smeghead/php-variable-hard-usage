<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Option;

final class GetOptions
{
    /**
     * @param array<string> $argv
     */
    public function __construct(private readonly array $argv)
    {
    }

    public function parse(): GetOptionsResult
    {
        $options = [];
        $args = [];
        $count = count($this->argv);
        for ($i = 0; $i < $count; $i++) {
            $arg = $this->argv[$i];
            if (strpos($arg, '--') === 0) {
                $key = substr($arg, 2);
                $value = true;
                if (strpos($key, '=') !== false) {
                    [$key, $value] = explode('=', $key, 2);
                }
                $options[$key] = $value;
            } else {
                $args[] = $arg;
            }
        }
        return new GetOptionsResult($options, array_slice($args, 1));
    }
}

final class GetOptionsResult {
    /**
     * @param array<string, string|bool> $options
     * @param array<string> $paths
     */
    public function __construct(
        public array $options,
        public array $paths,
    ) {}
}