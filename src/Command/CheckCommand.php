<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\AnalysisResult;

final class CheckCommand extends AbstractCommand
{
    use ScopesTrait;

    /** @var list<string> */
    private array $paths;
    private int $threshold;

    /**
     * @param list<string> $paths ディレクトリまたはファイルのパスリスト
     * @param int|null $threshold 閾値
     */
    public function __construct(array $paths, ?int $threshold = null)
    {
        $this->paths = $paths;
        $this->threshold = $threshold ?? 200; // デフォルト閾値は200
    }

    public function execute(): int
    {
        $analysis = $this->analyzePaths($this->paths);
        $results = $analysis['results'];
        $hasErrors = $analysis['hasErrors'];
        
        if (empty($results)) {
            return 1;
        }

        // 閾値チェックを行い結果を表示
        $exceedingScopes = $this->printResults($results);
        
        // 閾値を超えるスコープがあればエラーコード2を返す
        if (!empty($exceedingScopes['scopes'])) {
            return 2;
        }
        
        // 解析エラーがあればエラーコード1を返す
        return $hasErrors ? 1 : 0;
    }

    /**
     * @param list<AnalysisResult> $results
     * @return array{
     *   threshold: int,
     *   result: string,
     *   scopes: list<array{
     *     file: string,
     *     filename: string,
     *     namespace: string|null,
     *     name: string,
     *     variableHardUsage: int
     *   }>
     * } 閾値を超えたスコープの配列
     */
    protected function printResults(array $results): array
    {
        // 閾値を超えるスコープを検出
        $exceedingScopes = [];
        
        foreach ($results as $result) {
            foreach ($result->scopes as $scope) {
                $hardUsage = $scope->getVariableHardUsage();
                // 閾値以上の変数の酷使度を持つスコープのみ追加
                if ($hardUsage >= $this->threshold) {
                    $exceedingScopes[] = [
                        'file' => $result->filename,
                        'filename' => $result->filename,
                        'namespace' => $scope->namespace,
                        'name' => $scope->name,
                        'variableHardUsage' => $hardUsage
                    ];
                }
            }
        }

        // 酷使度でソート
        usort($exceedingScopes, function ($a, $b) {
            return $b['variableHardUsage'] <=> $a['variableHardUsage'];
        });

        // レポート作成
        $report = [
            'threshold' => $this->threshold,
            'result' => empty($exceedingScopes) ? 'success' : 'failure',
            'scopes' => $exceedingScopes
        ];

        // 結果を表示
        echo json_encode($report, JSON_PRETTY_PRINT) . PHP_EOL;
        
        return $report;
    }
}
