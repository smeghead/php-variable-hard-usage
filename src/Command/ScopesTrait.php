<?php

declare(strict_types=1);

namespace Smeghead\PhpVariableHardUsage\Command;

use Smeghead\PhpVariableHardUsage\Analyze\AnalysisResult;
use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\Exception\ParseFailedException;
use Smeghead\PhpVariableHardUsage\Parse\VariableParser;

trait ScopesTrait
{
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
     * @param list<string> $paths
     * @return array{results: list<AnalysisResult>, hasErrors: bool}
     */
    protected function analyzePaths(array $paths): array
    {
        $phpFiles = $this->pickupPhpFiles($paths);

        if (empty($phpFiles)) {
            fwrite(STDERR, "No PHP files found in specified paths\n");
            return ['results' => [], 'hasErrors' => true];
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
        
        return ['results' => $results, 'hasErrors' => $hasErrors];
    }
}