<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Example1.php';
require_once __DIR__ . '/Example2.php';
require_once __DIR__ . '/Example3.php';
require_once __DIR__ . '/Example4.php';
require_once __DIR__ . '/Example5.php';

class ExampleTest extends TestCase
{
    public function testCreateCsvLine1(): void
    {
        $this->assertSame('', createCsvLine1([]));
        $this->assertSame('1,2,3', createCsvLine1([1, 2, 3]));
        $this->assertSame('1,2,"helo"', createCsvLine1([1, 2, "helo"]));
    }

    public function testCreateCsvLine2(): void
    {
        $this->assertSame('', createCsvLine2([]));
        $this->assertSame('1,2,3', createCsvLine2([1, 2, 3]));
        $this->assertSame('1,2,"helo"', createCsvLine2([1, 2, "helo"]));
    }

    public function testCreateCsvLine3(): void
    {
        $this->assertSame('', createCsvLine3([]));
        $this->assertSame('1,2,3', createCsvLine3([1, 2, 3]));
        $this->assertSame('1,2,"helo"', createCsvLine3([1, 2, "helo"]));
    }

    public function testCreateCsvLine4(): void
    {
        $this->assertSame('', createCsvLine4([]));
        $this->assertSame('1,2,3', createCsvLine4([1, 2, 3]));
        $this->assertSame('1,2,"helo"', createCsvLine4([1, 2, "helo"]));
    }

    public function testCreateCsvLine5(): void
    {
        $this->assertSame('', createCsvLine5([]));
        $this->assertSame('1,2,3', createCsvLine5([1, 2, 3]));
        $this->assertSame('1,2,"helo"', createCsvLine5([1, 2, "helo"]));
    }
}