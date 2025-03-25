<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\AnalysisResult;

final class ScopesCommand extends AbstractCommand
{
    use ScopesTrait;

    /** @var list<string> */
    private array $paths;

    /**
     * @param list<string> $paths ディレクトリまたはファイルのパスリスト
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function execute(): int
    {
        $analysis = $this->analyzePaths($this->paths);
        $results = $analysis['results'];
        $hasErrors = $analysis['hasErrors'];
        
        if (empty($results)) {
            return 1;
        }

        // 複数ファイルの結果をまとめて表示
        $this->printResults($results);
        
        // エラーが一つでもあった場合は終了コードを1にする
        return $hasErrors ? 1 : 0;
    }

    /**
     * @param list<AnalysisResult> $results
     */
    protected function printResults(array $results): void
    {
        // スコープベースのレポートを生成
        $allScopes = [];
        foreach ($results as $result) {
            foreach ($result->scopes as $scope) {
                $allScopes[] = [
                    'file' => $result->filename,
                    'filename' => $result->filename,
                    'namespace' => $scope->namespace,
                    'name' => $scope->name,
                    'variableHardUsage' => $scope->getVariableHardUsage()
                ];
            }
        }

        // 酷使度でソート
        usort($allScopes, function ($a, $b) {
            return $b['variableHardUsage'] <=> $a['variableHardUsage'];
        });

        // 結果を表示
        echo json_encode(['scopes' => $allScopes], JSON_PRETTY_PRINT) . PHP_EOL;
    }
}