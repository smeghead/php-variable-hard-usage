<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\VariableParser;

final class ScopesCommand extends AbstractCommand
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function execute(): void
    {
        if (!is_dir($this->directory)) {
            fwrite(STDERR, "Directory not found: {$this->directory}\n");
            return;
        }

        $phpFiles = $this->findPhpFiles($this->directory);
        if (empty($phpFiles)) {
            fwrite(STDERR, "No PHP files found in: {$this->directory}\n");
            return;
        }

        $results = [];
        foreach ($phpFiles as $file) {
            try {
                $content = file_get_contents($file);
                if ($content === false) {
                    fwrite(STDERR, "Failed to read file: {$file}\n");
                    continue;
                }

                $parser = new VariableParser();
                $parseResult = $parser->parse($content);
                $analyzer = new VariableAnalyzer($file, $parseResult->functions);
                $results[] = $analyzer->analyze();
            } catch (\Exception $e) {
                fwrite(STDERR, "Error analyzing {$file}: {$e->getMessage()}\n");
            }
        }

        // 複数ファイルの結果をまとめて表示
        $this->printResults($results);
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
                    'file' => $result->filename,
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