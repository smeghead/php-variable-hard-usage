<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Core\Analyzer;
use Smeghead\PhpVariableHardUsage\Core\AnalysisResult;

class AnalyzerTest extends TestCase
{
    private string $fixtureDir = __DIR__ . '/fixtures';

    public function testAnalyze(): void
    {
        $analyzer = new Analyzer();
        $content = file_get_contents($this->fixtureDir . '/function.php');
        $result = $analyzer->analyze($content);

        $this->assertInstanceOf(AnalysisResult::class, $result);
        $this->assertEquals(2, $result->getVariableCount());
        $this->assertEquals(0, $result->getScopeIssues());
        $this->assertEquals(1, $result->getUpdateFrequency());
    }
}