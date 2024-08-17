<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Cpu2
 */
class Cpu2Test extends TestCase
{
    /** @var mixed[] */
    private array $board = [];

    /** @var mixed */
    private $cardStub;

    protected function setUp(): void
    {
        // set up 25 empty slots
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $this->board[$i . $j] = null;
            }
        }

        $this->cardStub = $this->createStub(CardInterface::class);
        $this->cardStub->method("getSuit")->willReturn("hearts");
    }



    /**
     * Get a suggestion (expected slot = 11, in preferred column for hearts)
     */
    public function testSuggestPreferredPlacement(): void
    {
        $res = Cpu2::suggestPlacement($this->board, $this->cardStub);
        $this->assertEquals(11, $res);
    }



    /**
     * Get a suggestion (expected slot = 15, trash column)
     */
    public function testSuggestTrashPlacement(): void
    {
        // populate the hearts column (col1)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, "1")) {
                $this->board[$slot] = $this->cardStub;
            }
        }

        $res = Cpu2::suggestPlacement($this->board, $this->cardStub);
        $this->assertEquals(15, $res);
    }



    /**
     * Get a suggestion (expected slot = 12, first empty)
     */
    public function testSuggestFirstEmptyPlacement(): void
    {
        // populate the hearts column (col1), and trash column (col5)
        foreach ($this->board as $slot => $card) {
            if (str_ends_with($slot, "1") || str_ends_with($slot, "5")) {
                $this->board[$slot] = $this->cardStub;
            }
        }

        $res = Cpu2::suggestPlacement($this->board, $this->cardStub);
        $this->assertEquals(12, $res);
    }



    /**
     * Get no suggestion (board is full)
     */
    public function testSuggestNoPlacement(): void
    {
        // populate the board
        foreach ($this->board as $slot => $card) {
            $this->board[$slot] = $this->cardStub;
        }

        $res = Cpu2::suggestPlacement($this->board, $this->cardStub);
        $this->assertNull($res);
    }
}
