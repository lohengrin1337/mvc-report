<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Cpu3
 */
class Cpu3Test extends TestCase
{
    private array $board = [];
    private CardInterface $cardStub1; // 1 of hearts
    private CardInterface $cardStub2; // 2 of hearts

    protected function setUp(): void
    {
        // set up 25 empty slots
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
            $this->board[$i . $j] = null;
            }
        }

        $this->cardStub1 = $this->createStub(CardInterface::class);
        $this->cardStub1->method("getSuit")->willReturn("hearts");
        $this->cardStub1->method("getRank")->willReturn(1);

        $this->cardStub2 = $this->createStub(CardInterface::class);
        $this->cardStub2->method("getSuit")->willReturn("hearts");
        $this->cardStub2->method("getRank")->willReturn(2);
    }


    /**
     * Get a suggestion (expected slot = 21, in preferred column for hearts and preferred row)
     */
    public function testSuggestPreferredPlacement1(): void
    {
        // populate slot 22 with rank 1
        $this->board[22] = $this->cardStub1;

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub1);
        $this->assertEquals(21, $res);
    }


    /**
     * Get a suggestion (expected slot = 11, in preferred column for hearts)
     */
    public function testSuggestPreferredPlacement2(): void
    {
        $res = Cpu3::suggestPlacement($this->board, $this->cardStub2);
        $this->assertEquals(11, $res);
    }



    /**
     * Get a suggestion (expected slot = 25, trash column, and preferred row)
     */
    public function testSuggestTrashPlacement1(): void
    {
        // populate the hearts column (col1)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, 1)) {
                $this->board[$slot] = $this->cardStub1;
            }
        }

        // populate slot 22 with rank 1
        $this->board[22] = $this->cardStub1;

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub1);
        $this->assertEquals(25, $res);
    }



    /**
     * Get a suggestion (expected slot = 15, trash column)
     */
    public function testSuggestTrashPlacement2(): void
    {
        // populate the hearts column (col1)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, 1)) {
                $this->board[$slot] = $this->cardStub1;
            }
        }

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub2);
        $this->assertEquals(15, $res);
    }



    /**
     * Get a suggestion (expected slot = 23, first empty in preferred row)
     */
    public function testSuggestFirstEmptyPlacement1(): void
    {
        // populate the hearts column (col1), and trash column (col5)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, 1) || str_ends_with($slot, 5)) {
                $this->board[$slot] = $this->cardStub1;
            }
        }

        // populate slot 22 with rank 1
        $this->board[22] = $this->cardStub1;

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub1);
        $this->assertEquals(23, $res);
    }



    /**
     * Get a suggestion (expected slot = 12, first empty)
     */
    public function testSuggestFirstEmptyPlacement2(): void
    {
        // populate the hearts column (col1), and trash column (col5)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, 1) || str_ends_with($slot, 5)) {
                $this->board[$slot] = $this->cardStub1;
            }
        }

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub2);
        $this->assertEquals(12, $res);
    }



    /**
     * Get no suggestion (board is full)
     */
    public function testSuggestNoPlacement(): void
    {
        // populate the board
        foreach ($this->board as $slot => $card) {
            $this->board[$slot] = $this->cardStub1;
        }

        $res = Cpu3::suggestPlacement($this->board, $this->cardStub2);
        $this->assertNull($res);
    }
}
