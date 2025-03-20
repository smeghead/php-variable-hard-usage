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
        $this->assertSame('smallFunction', $functions[0]->name);
        $this->assertSame(null, $functions[0]->namespace);
        $this->assertCount(2, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('num', $vars[0]->name);
        $this->assertSame(5, $vars[0]->lineNumber, 'first $num (5)');
        $this->assertSame(true, $vars[0]->assigned, 'first $num (5) asign');
        $this->assertSame('num', $vars[1]->name);
        $this->assertSame(10, $vars[1]->lineNumber, 'second $num (10)');
        $this->assertSame(false, $vars[1]->assigned, 'second $num (10) not reference');
    }

    public function testParseClass(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/Clazz.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertSame('Smeghead\PhpVariableHardUsage\Fixtures', $functions[0]->namespace);
        $this->assertSame('Clazz::bigFunction', $functions[0]->name);
        $this->assertCount(4, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('num', $vars[0]->name);
        $this->assertSame(11, $vars[0]->lineNumber, 'first $num (11)');
        $this->assertSame('num', $vars[1]->name);
        $this->assertSame(13, $vars[1]->lineNumber, 'second $num (13)');
        $this->assertSame('num', $vars[2]->name);
        $this->assertSame(14, $vars[2]->lineNumber, 'second $num (14)');
        $this->assertSame('num', $vars[3]->name);
        $this->assertSame(17, $vars[3]->lineNumber, 'second $num (17)');
    }

    public function testParseAnonymousFunction(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/AnonymousFunction.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertSame('Expr_Closure@4', $functions[0]->name);
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

    public function testParseNamespace(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/namespace.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(2, $functions);
        $this->assertSame('SomeNamespace', $functions[0]->namespace);
        $this->assertSame('someFunction', $functions[0]->name);
        $this->assertCount(0, $functions[0]->getVariables());

        $this->assertSame('OtherNamespace', $functions[1]->namespace);
        $this->assertSame('otherFunction', $functions[1]->name);
        $this->assertCount(0, $functions[1]->getVariables());
    }

    public function testParseAssignOperator(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/assign_operator.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $functions = $result->functions;
        $this->assertCount(1, $functions);
        $this->assertSame('assignFunction', $functions[0]->name);
        $this->assertCount(14, $functions[0]->getVariables());

        $vars = $functions[0]->getVariables();
        $this->assertSame('num', $vars[0]->name);
        $this->assertSame(5, $vars[0]->lineNumber);
        $this->assertSame(true, $vars[0]->assigned, '$num = 1;');
        $this->assertSame('num', $vars[1]->name);
        $this->assertSame(6, $vars[1]->lineNumber);
        $this->assertSame(true, $vars[1]->assigned, '$num += 1;');
        $this->assertSame('num', $vars[2]->name);
        $this->assertSame(7, $vars[2]->lineNumber);
        $this->assertSame(true, $vars[2]->assigned, '$num -= 1;');
        $this->assertSame('num', $vars[3]->name);
        $this->assertSame(8, $vars[3]->lineNumber);
        $this->assertSame(true, $vars[3]->assigned, '$num *= 1;');
        $this->assertSame('num', $vars[4]->name);
        $this->assertSame(9, $vars[4]->lineNumber);
        $this->assertSame(true, $vars[4]->assigned);
        $this->assertSame('num', $vars[5]->name);
        $this->assertSame(10, $vars[5]->lineNumber);
        $this->assertSame(true, $vars[5]->assigned);
        $this->assertSame('num', $vars[6]->name);
        $this->assertSame(11, $vars[6]->lineNumber);
        $this->assertSame(true, $vars[6]->assigned);
        $this->assertSame('num', $vars[7]->name);
        $this->assertSame(12, $vars[7]->lineNumber);
        $this->assertSame(true, $vars[7]->assigned);
        $this->assertSame('num', $vars[8]->name);
        $this->assertSame(13, $vars[8]->lineNumber);
        $this->assertSame(true, $vars[8]->assigned);
        $this->assertSame('num', $vars[9]->name);
        $this->assertSame(14, $vars[9]->lineNumber);
        $this->assertSame(true, $vars[9]->assigned);
        $this->assertSame('num', $vars[10]->name);
        $this->assertSame(15, $vars[10]->lineNumber);
        $this->assertSame(true, $vars[10]->assigned);
        $this->assertSame('num', $vars[11]->name);
        $this->assertSame(16, $vars[11]->lineNumber);
        $this->assertSame(true, $vars[11]->assigned);
        $this->assertSame('num', $vars[12]->name);
        $this->assertSame(17, $vars[12]->lineNumber);
        $this->assertSame(true, $vars[12]->assigned);
        $this->assertSame('num', $vars[13]->name);
        $this->assertSame(18, $vars[13]->lineNumber);
        $this->assertSame(true, $vars[13]->assigned);
    }
}