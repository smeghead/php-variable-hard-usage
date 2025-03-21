<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\AnalysisResult;
use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\Exception\ParseFailedException;
use Smeghead\PhpVariableHardUsage\Parse\VariableParser;

final class ScopesCommand extends AbstractCommand
{
    /** @var list<string> */
    private array $paths;

    /**
     * @param list<string> $paths ディレクトリまたはファイルのパスリスト
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param list<string> $paths
     * @return list<string>
     */
    private function pickupPhpFiles(array $paths): array
    {
        $phpFiles = [];

        // 各パスを処理
        foreach ($paths as $path) {
            if (is_dir($path)) {
                // ディレクトリの場合は再帰的にPHPファイルを収集
                $dirFiles = $this->findPhpFiles($path);
                $phpFiles = array_merge($phpFiles, $dirFiles);
            } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                // 単一のPHPファイルの場合
                $phpFiles[] = $path;
            } else {
                fwrite(STDERR, "Invalid path: {$path}\n");
            }
        }

        return $phpFiles;
    }
    
    private function analyzeFile(string $file): AnalysisResult
    {
        $parser = new VariableParser();
        $content = file_get_contents($file);
        if ($content === false) {
            throw new ParseFailedException("Failed to read file: {$file}");
        }

        $parseResult = $parser->parse($content);
        $analyzer = new VariableAnalyzer($file, $parseResult->functions);
        return $analyzer->analyze();
    }

    public function execute(): int
    {
        $phpFiles = $this->pickupPhpFiles($this->paths);

        if (empty($phpFiles)) {
            fwrite(STDERR, "No PHP files found in specified paths\n");
            return 1;
        }

        // 重複を削除
        $phpFiles = array_unique($phpFiles);
        
        $results = [];
        $hasErrors = false;
        
        foreach ($phpFiles as $file) {
            try {
                $results[] = $this->analyzeFile($file);
            } catch (\Exception $e) {
                fwrite(STDERR, "Error analyzing {$file}: {$e->getMessage()}\n");
                $hasErrors = true;
            }
        }
        
        if (empty($results)) {
            return 1;
        }

        // 複数ファイルの結果をまとめて表示
        $this->printResults($results);
        
        // エラーが一つでもあった場合は終了コードを1にする
        return $hasErrors ? 1 : 0;
    }

    /**
     * @return list<string>
     */
    private function findPhpFiles(string $directory): array
    {
        $result = [];
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $result[] = $file->getPathname();
            }
        }

        return $result;
    }

    /**
     * @param list<\Smeghead\PhpVariableHardUsage\Analyze\AnalysisResult> $results
     */
    private function printResults(array $results): void
    {
        // スコープベースのレポートを生成
        $allScopes = [];
        foreach ($results as $result) {
            foreach ($result->scopes as $scope) {
                $allScopes[] = [
                    'file' => $result->filename,        // 既存の 'file' プロパティを維持
                    'filename' => $result->filename,    // 新しく 'filename' プロパティを追加
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