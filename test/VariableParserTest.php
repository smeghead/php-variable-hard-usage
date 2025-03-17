<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Parse\ParseResult;
use Smeghead\PhpVariableHardUsage\Parse\VariableParser;

class VariableParserTest extends TestCase
{
    private string $fixtureDir = __DIR__ . '/fixtures';

    public function testParseFunction(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/function.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertEquals('smallFunction', $functions[0]->name);
        $this->assertCount(2, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('num', $vars[0]->name);
        $this->assertSame(5, $vars[0]->lineNumber, 'first $num (5)');
        $this->assertSame('num', $vars[1]->name);
        $this->assertSame(10, $vars[1]->lineNumber, 'second $num (10)');
    }

    public function testParseClass(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/Clazz.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertEquals('Clazz::bigFunction', $functions[0]->name);
        $this->assertCount(4, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('num', $vars[0]->name);
        $this->assertSame(9, $vars[0]->lineNumber, 'first $num (9)');
        $this->assertSame('num', $vars[1]->name);
        $this->assertSame(11, $vars[1]->lineNumber, 'second $num (11)');
        $this->assertSame('num', $vars[2]->name);
        $this->assertSame(12, $vars[2]->lineNumber, 'second $num (12)');
        $this->assertSame('num', $vars[3]->name);
        $this->assertSame(15, $vars[3]->lineNumber, 'second $num (15)');
    }

    public function testParseAnonymousFunction(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/AnonymousFunction.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertEquals('Expr_Closure@4', $functions[0]->name);
        $this->assertCount(4, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('a', $vars[0]->name);
        $this->assertSame(4, $vars[0]->lineNumber, 'first $a (4)');
        $this->assertSame('b', $vars[1]->name);
        $this->assertSame(4, $vars[1]->lineNumber, 'second $b (4)');
        $this->assertSame('a', $vars[2]->name);
        $this->assertSame(5, $vars[2]->lineNumber, 'second $a (5)');
        $this->assertSame('b', $vars[3]->name);
        $this->assertSame(5, $vars[3]->lineNumber, 'second $b (5)');
    }
}