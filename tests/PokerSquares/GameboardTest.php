<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\Exception\InvalidSlotException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Gameboard.
 */
class GameboardTest extends TestCase
{
    private Gameboard $gb;

    private CardInterface $cardStub;

    protected function setUp(): void
    {
        $this->gb = new Gameboard(); // empty board

        $this->cardStub = $this->createStub(CardInterface::class);
        $this->cardStub->method("getAsString")->willReturn("svg-card-as");
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateGameboard(): void
    {
        $this->assertInstanceOf(Gameboard::class, $this->gb);
    }



    /**
     * Verify board has 25 empty slots
     */
    public function testBoardIsEmpty(): void
    {
        $this->assertCount(25, $this->gb->getAsString());
        $this->assertContainsOnly("null", $this->gb->getAsString());
    }



    /**
     * Place a card (stub = ace of spades) at row 2 col 3 - assert
     */
    public function testPlaceCardValidSlot(): void
    {
        $this->gb->placeCard("23", $this->cardStub);
        $res = $this->gb->getAsString();
        $this->assertEquals("svg-card-as", $res["23"]);
    }



    /**
     * Place a card (stub = ace of spades) at row 7 col 1 - expectException
     */
    public function testPlaceCardInvalidSlot(): void
    {
        $this->expectException(InvalidSlotException::class);
        $this->gb->placeCard("71", $this->cardStub);
    }



    /**
     * Place two cards at same slot - expectException
     */
    public function testPlaceCardInvalidReuseSlot(): void
    {
        $this->gb->placeCard("23", $this->cardStub);
        $this->expectException(InvalidSlotException::class);
        $this->gb->placeCard("23", $this->cardStub);
    }



    /**
     * Place 25 cards and assert board is full
     */
    public function testBordIsFull(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $this->gb->placeCard("$i$j", $this->cardStub);
            }
        }

        $this->assertTrue($this->gb->boardIsFull());
    }
}
