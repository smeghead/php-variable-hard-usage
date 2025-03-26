<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Option\GetOptions;

class GetOptionsTest extends TestCase
{
    public function testEmpty(): void
    {
        $argv = ['script.php'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertSame([], $result->paths);
    }

    public function testHelp(): void
    {
        $argv = ['script.php', '--help'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertArrayHasKey('help', $result->options);
        $this->assertSame(true, $result->options['help']);
    }

    public function testVersion(): void
    {
        $argv = ['script.php', '--version'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertArrayHasKey('version', $result->options);
        $this->assertSame(true, $result->options['version']);
    }

    public function testSingle(): void
    {
        $argv = ['script.php', 'single', 'file.php'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertSame(['single', 'file.php'], $result->paths);
    }

    public function testScopes(): void
    {
        $argv = ['script.php', 'scopes', 'dir1', 'dir2'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertSame(['scopes', 'dir1', 'dir2'], $result->paths);
    }

    public function testCheckWithThreshold(): void
    {
        $argv = ['script.php', 'check', 'dir1', 'dir2', '--threshold=200'];
        $sut = new GetOptions($argv);
        $result = $sut->parse();
        $this->assertSame(['check', 'dir1', 'dir2'], $result->paths);
        $this->assertArrayHasKey('threshold', $result->options);
        $this->assertSame('200', $result->options['threshold']);
    }
}