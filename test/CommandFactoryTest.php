<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Smeghead\PhpVariableHardUsage\Command\CheckCommand;
use Smeghead\PhpVariableHardUsage\Command\HelpCommand;
use Smeghead\PhpVariableHardUsage\Command\ScopesCommand;
use Smeghead\PhpVariableHardUsage\Command\SingleCommand;
use Smeghead\PhpVariableHardUsage\Command\VersionCommand;
use Smeghead\PhpVariableHardUsage\Option\CommandFactory;

class CommandFactoryTest extends TestCase
{
    public function testParseNoArgs(): void
    {
        $argv = [];
        $sut = new CommandFactory([], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(HelpCommand::class, $result);
    }

    public function testParseHelp(): void
    {
        $argv = [];
        $sut = new CommandFactory(['help' => false], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(HelpCommand::class, $result);
    }

    public function testParseVersion(): void
    {
        $argv = [];
        $sut = new CommandFactory(['version' => false], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(VersionCommand::class, $result);
    }

    public function testParseSingle(): void
    {
        $argv = ['single', 'file.php'];
        $sut = new CommandFactory([], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(SingleCommand::class, $result);
    }

    public function testParseScopes(): void
    {
        $argv = ['scopes', 'dir1', 'dir2'];
        $sut = new CommandFactory([], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(ScopesCommand::class, $result);
    }

    public function testParseCheck(): void
    {
        $argv = ['check', 'dir1', 'dir2'];
        $sut = new CommandFactory(['threshold' => '200'], $argv);
        $result = $sut->create();
        $this->assertInstanceOf(CheckCommand::class, $result);
    }
}