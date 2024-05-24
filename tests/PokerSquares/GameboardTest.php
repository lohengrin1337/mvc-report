<?php

namespace App\PokerSquares;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Gameboard.
 */
class GameboardTest extends TestCase
{
    private Gameboard $board;

    protected function setUp(): void
    {
        $this->board = new Gameboard(); // empty board
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateGameboard(): void
    {
        $this->assertInstanceOf(Gameboard::class, $this->board);
    }



    /**
     * Verify board has 25 empty slots
     */
    public function testBoardIsEmpty(): void
    {
        $this->assertCount(25, $this->board->getAsString());
        $this->assertContainsOnly("null", $this->board->getAsString());
    }
}
