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
        $argv = ['script.php'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(HelpCommand::class, $result);
    }

    public function testParseHelp(): void
    {
        $argv = ['script.php', '--help'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(HelpCommand::class, $result);
    }

    public function testParseVersion(): void
    {
        $argv = ['script.php', '--version'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(VersionCommand::class, $result);
    }

    public function testParseSingle(): void
    {
        $argv = ['script.php', 'single', 'file.php'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(SingleCommand::class, $result);
    }

    public function testParseScopes(): void
    {
        $argv = ['script.php', 'scopes', 'dir1', 'dir2'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(ScopesCommand::class, $result);
    }

    public function testParseCheck(): void
    {
        $argv = ['script.php', 'check', '--threshold', '200', 'dir1', 'dir2'];
        $sut = new CommandFactory($argv);
        $result = $sut->create();
        $this->assertInstanceOf(CheckCommand::class, $result);
    }
}