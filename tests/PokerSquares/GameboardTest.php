<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\Exception\InvalidSlotException;
use App\Entity\Board;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Gameboard.
 */
class GameboardTest extends TestCase
{
    private Gameboard $gbEmpty;  // empty gameboard

    private Gameboard $gbFull;  // full gameboard (card stubs)

    private CardInterface $cardStub;

    protected function setUp(): void
    {
        $this->gbEmpty = new Gameboard();

        $this->cardStub = $this->createStub(CardInterface::class);
        $this->cardStub->method("getAsString")->willReturn("svg-card-as");
        $this->gbFull = new Gameboard();
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                $this->gbFull->placeCard("$i$j", $this->cardStub);
            }
        }
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateGameboard(): void
    {
        $this->assertInstanceOf(Gameboard::class, $this->gbEmpty);
        $this->assertInstanceOf(Gameboard::class, $this->gbFull);
    }



    /**
     * Get board
     */
    public function testGetBoard(): void
    {
        $this->assertIsArray($this->gbFull->getBoard());
    }



    /**
     * Export as entity
     */
    public function testExportEntity(): void
    {
        $boardEntity = $this->gbFull->exportAsEntity();
        $this->assertInstanceOf(Board::class, $boardEntity);
    }



    /**
     * Verify board has 25 empty slots
     */
    public function testBoardIsEmpty(): void
    {
        $this->assertCount(25, $this->gbEmpty->getBoardView());
        $this->assertContainsOnly("null", $this->gbEmpty->getBoardView());
    }



    /**
     * Place a card (stub = ace of spades) at row 2 col 3 - assert
     */
    public function testPlaceCardValidSlot(): void
    {
        $this->gbEmpty->placeCard("23", $this->cardStub);
        $res = $this->gbEmpty->getBoardView();
        $this->assertEquals("svg-card-as", $res["23"]);
    }



    /**
     * Place a card (stub = ace of spades) at row 7 col 1 - expectException
     */
    public function testPlaceCardInvalidSlot(): void
    {
        $this->expectException(InvalidSlotException::class);
        $this->gbEmpty->placeCard("71", $this->cardStub);
    }



    /**
     * Place two cards at same slot - expectException
     */
    public function testPlaceCardInvalidReuseSlot(): void
    {
        $this->gbEmpty->placeCard("23", $this->cardStub);
        $this->expectException(InvalidSlotException::class);
        $this->gbEmpty->placeCard("23", $this->cardStub);
    }



    /**
     * Place 1 card and assert board has one card
     */
    public function testBoardHasOneCard(): void
    {
        $this->gbEmpty->placeCard("33", $this->cardStub);
        $this->assertTrue($this->gbEmpty->boardHasOneCard());
    }



    /**
     * Place 25 cards and assert board is full
     */
    public function testBordIsFull(): void
    {
        $this->assertTrue($this->gbFull->boardIsFull());
    }



    /**
     * Get all hands of full board, assert array and CardInterface instance
     */
    public function testgetAllHandsFull(): void
    {
        $res = $this->gbFull->getAllHands();
        $this->assertIsArray($res);

        $card = $res["row1"][0];
        $this->assertInstanceOf(CardInterface::class, $card);
    }



    /**
     * Get all hands of empty board, assert array and null
     */
    public function testgetAllHandsEmpty(): void
    {
        $res = $this->gbEmpty->getAllHands();
        $this->assertIsArray($res);

        $card = $res["row1"][0];
        $this->assertNull($card);
    }
}
