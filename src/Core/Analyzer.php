<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Core;

final class Analyzer
{
    public function analyze(string $content): AnalysisResult
    {
        // ここに解析ロジックを追加します
        // 仮の解析結果を返します
        return new AnalysisResult(0, 0, 0);
    }
}