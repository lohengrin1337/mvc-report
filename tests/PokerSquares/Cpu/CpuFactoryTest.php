<?php

namespace App\PokerSquares\Cpu;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CpuFactory
 */
class CpuFactoryTest extends TestCase
{
    /**
     * Get classname of Cpu1 for existing level 1
     */
    public function testGetCpuIntel(): void
    {
        $this->assertEquals(Cpu1::class, CpuFactory::getCpuIntel(1));
    }



    /**
     * Try to get classname for non existing level 4 and expect exception
     */
    public function testGetNoCpuIntel(): void
    {
        $this->expectException("Exception");
        CpuFactory::getCpuIntel(4);
    }
}
