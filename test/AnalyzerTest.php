<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Core\Analyzer;
use Smeghead\PhpVariableHardUsage\Core\AnalysisResult;

class AnalyzerTest extends TestCase
{
    public function testAnalyze(): void
    {
        $analyzer = new Analyzer();
        $content = '<?php $a = 1; $b = 2; $a = 3; ?>';
        $result = $analyzer->analyze($content);

        $this->assertInstanceOf(AnalysisResult::class, $result);
        $this->assertEquals(2, $result->getVariableCount());
        $this->assertEquals(0, $result->getScopeIssues());
        $this->assertEquals(1, $result->getUpdateFrequency());
    }
}