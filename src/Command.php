<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

final class Command
{
    public function run(array $argv): void
    {
        if (count($argv) < 2) {
            $this->printHelp();
            return;
        }

        $filePath = $argv[1];
        if (!file_exists($filePath)) {
            echo "File not found: $filePath\n";
            return;
        }

        $analyzer = new Analyzer();
        $analyzer->analyze($filePath);
    }

    private function printHelp(): void
    {
        echo "Usage: php bin/php-variable-hard-usage [source_file]\n";
        echo "Options:\n";
        echo "  --help    Display help information\n";
        echo "  --version Show the version of the tool\n";
    }
}