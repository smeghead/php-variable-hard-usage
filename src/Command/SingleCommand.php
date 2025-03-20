<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\VariableParser;

final class SingleCommand extends AbstractCommand
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function execute(): void
    {
        if (!file_exists($this->filePath)) {
            fwrite(STDERR, "File not found: {$this->filePath}\n");
            return;
        }

        $parser = new VariableParser();
        $content = file_get_contents($this->filePath);
        if ($content === false) {
            fwrite(STDERR, "Failed to read file: {$this->filePath}\n");
            return;
        }

        $parseResult = $parser->parse($content);
        $analyzer = new VariableAnalyzer($this->filePath, $parseResult->functions);
        $result = $analyzer->analyze();
        echo $result->format();
    }
}