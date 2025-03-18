<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Analyze\VariableAnalyzer;
use Smeghead\PhpVariableHardUsage\Parse\Func;
use Smeghead\PhpVariableHardUsage\Parse\VarReference;

class VariableAnalizerTest extends TestCase
{
    public function testAnalyzeFunctionSimple(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 3));

        $sut = new VariableAnalyzer([$func]);
        $result = $sut->analyze();
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        $this->assertSame(2, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage);
    }

    public function testAnalyzeFunctionLong(): void
    {
        $func = new Func(null, 'testFunction');
        $func->addVariable(new VarReference('a', 1));
        $func->addVariable(new VarReference('a', 2));
        $func->addVariable(new VarReference('a', 100));

        $sut = new VariableAnalyzer([$func]);
        $result = $sut->analyze();
        $scopes = $result->scopes;

        $this->assertCount(1, $scopes);
        $this->assertSame('testFunction', $scopes[0]->name);
        // (1 + 2 + 100) / 3 = 34
        // abs(34 - 1) + abs(34 - 2) + abs(34 - 100) = 33 + 32 + 66 = 131
        $this->assertSame(131, $scopes[0]->getAnalyzedVariables()[0]->variableHardUsage);
    }
}