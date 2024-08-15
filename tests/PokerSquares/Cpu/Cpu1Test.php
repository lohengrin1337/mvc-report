<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Cpu1.
 */
class Cpu1Test extends TestCase
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
    }



    /**
     * Get a suggestion (expected slot = 23)
     */
    public function testSuggestPlacement(): void
    {
        // set the seed of rand()
        srand(123);

        $res = Cpu1::suggestPlacement($this->board, $this->cardStub);
        $this->assertEquals(23, $res);

        // reset the seed
        srand();
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

        $res = Cpu1::suggestPlacement($this->board, $this->cardStub);
        $this->assertNull($res);
    }
}
