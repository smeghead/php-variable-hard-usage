<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Core\ParseResult;
use Smeghead\PhpVariableHardUsage\Core\VariableParser;

class VariableParserTest extends TestCase
{
    private string $fixtureDir = __DIR__ . '/fixtures';

    public function testParse(): void
    {
        $parser = new VariableParser();
        $content = file_get_contents($this->fixtureDir . '/function.php');
        $result = $parser->parse($content);

        $this->assertInstanceOf(ParseResult::class, $result);
        $fuctions = $result->getfunctions();
        $this->assertCount(1, $fuctions);
        $this->assertEquals('smallFunction', $fuctions[0]->getName());
        $this->assertCount(2, $fuctions[0]->getVariables());

        $vars = $fuctions[0]->getVariables();
        $this->assertSame('num', $vars[0]->getName());
        $this->assertSame(5, $vars[0]->getLineNumber(), 'first $num (5)');
        $this->assertSame('num', $vars[1]->getName());
        $this->assertSame(10, $vars[1]->getLineNumber(), 'second $num (10)');
    }
}