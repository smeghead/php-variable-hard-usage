<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage;

class Analyzer
{
    public function analyze(string $filePath): void
    {
        $content = file_get_contents($filePath);
        // ここに解析ロジックを追加します
        echo "Analyzing file: $filePath\n";
    }
}