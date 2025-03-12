<?php

namespace PhpVariableHardUsage;

class Analyzer
{
    public function analyze($filePath)
    {
        $content = file_get_contents($filePath);
        // ここに解析ロジックを追加します
        echo "Analyzing file: $filePath\n";
    }
}