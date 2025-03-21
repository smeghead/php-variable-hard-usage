<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\Func;
use Smeghead\PhpVariableHardUsage\Parse\VarReference;

class VariableAnalizerTest extends TestCase
{
    public function testAnalyzeEmpty(): void
    {
        $sut = new VariableAnalyzer('target.php', []);
        $result = $sut->analyze();
        $this->assertSame('target.php', $result->filename);
        $scopes = $result->scopes;

        $this->assertCount(0, $scopes);
    }

    public function testAnalyzeFunctionSimple(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 3));

        $sut = new VariableAnalyzer('target.php', [$func]);
        $result = $sut->analyze();
        $this->assertSame('target.php', $result->filename);
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        $this->assertSame(3, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage, '0 + 1 + 2');
    }

    public function testAnalyzeFunctionLong(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 100));

        $sut = new VariableAnalyzer('target.php', [$func]);
        $result = $sut->analyze();
        $this->assertSame('target.php', $result->filename);
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        $this->assertSame(100, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage, '(1 - 1) + (2 - 1) + (100 - 1)');
    }

    public function testAnalyzeFunctionLongAssignedVariable(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1, true));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 100));

        $sut = new VariableAnalyzer('target.php', [$func]);
        $result = $sut->analyze();
        $this->assertSame('target.php', $result->filename);
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        $this->assertSame(100, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage, '(1 - 1) * 2 + (2 - 1) + (100 - 1)');
    }

    public function testAnalyzeFunctionLongMultipleAssignedVariable(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1, true));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 100, true));

        $sut = new VariableAnalyzer('target.php', [$func]);
        $result = $sut->analyze();
        $this->assertSame('target.php', $result->filename);
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        $this->assertSame(199, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage, '(1 - 1) * 2 + (2 - 1) + (100 - 1) * 2');
    }
}