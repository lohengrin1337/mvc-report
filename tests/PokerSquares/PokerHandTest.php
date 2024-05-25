<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerHand
 */
class PokerHandTest extends TestCase
{
    private PokerHand $ph;

    protected function setUp(): void
    {
        $cardStubs = [];
        for ($i=0; $i < 5; $i++) { 
            $cardStubs[] = $this->createStub(CardInterface::class);
        }

        $this->ph = new PokerHand($cardStubs); // poker hand with 5 cards (stubs)
    }



    /**
     * Construct object and verify instance
     */
    public function testCreatePokerHand(): void
    {
        $this->assertInstanceOf(PokerHand::class, $this->ph);
    }



    /**
     * Create object with invalid arg - expectException
     */
    public function testCreateInvalidPokerHand(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PokerHand(["invalid object"]);
    }



    /**
     * Return type of getPoints is int
     */
    public function testCallGetPoints(): void
    {
        $this->assertIsInt($this->ph->getPoints());
    }
}
