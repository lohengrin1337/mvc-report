<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Board Entity.
 */
class BoardTest extends TestCase
{
    private Board $board;

    protected function setUp(): void
    {
        $board = new Board();
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $data[$i . $j] = "svg-card-5h";
            }
        }

        $board->setData($data);

        $this->board = $board;
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Board::class, $this->board);
    }



    /**
     * Get data
     */
    public function testGetData(): void
    {
        $data = $this->board->getData();
        $this->assertEquals("svg-card-5h", $data[22]);
    }



    /**
     * Get id
     */
    public function testGetId(): void
    {
        $this->assertNull($this->board->getId());
    }
}
