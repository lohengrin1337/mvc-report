<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    private CardHand $hand;

    private CardDeck $deckStub; // top card = ♥7

    protected function setUp(): void
    {
        $this->hand = new CardHand();   // empty hand

        $cardStub = $this->createStub(CardInterface::class);
        $cardStub->method('getSuit')->willReturn('hearts');
        $cardStub->method('getRank')->willReturn(7);
        $cardStub->method('getAsString')->willReturn("[♥7]");
        $cardStub->method('setRank')->willReturn(true);

        $deckStub = $this->createStub(CardDeck::class);
        $deckStub->method('draw')->willReturn($cardStub);
        $this->deckStub = $deckStub;
    }



    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardHand()
    {
        $this->assertInstanceOf(CardHand::class, $this->hand);
        $this->assertEquals(0, $this->hand->cardCount());
    }



    /**
     * Draw 1 card (stub) from deck (stub), and verify get-methods
     */
    public function testDrawOneFromDeck()
    {
        $this->hand->draw($this->deckStub);
        $this->assertEquals(1, $this->hand->cardCount());
        $this->assertEquals(7, $this->hand->rankSum());
        $this->assertEquals(["[♥7]"], $this->hand->getAsString());
        $this->assertInstanceOf(CardInterface::class, $this->hand->getLastCard());
    }



    /**
     * Draw 3 cards (stub) from deck (stub), and verify get-methods
     */
    public function testDrawThreeFromDeck()
    {
        $this->hand->draw($this->deckStub, 3);
        $this->assertEquals(3, $this->hand->cardCount());
        $this->assertEquals(21, $this->hand->rankSum());
        $this->assertEquals(["[♥7]", "[♥7]", "[♥7]"], $this->hand->getAsString());
        $this->assertInstanceOf(CardInterface::class, $this->hand->getLastCard());
    }



    /**
     * Draw 1 card (stub) from deck (stub), set rank, and verify sum
     */
    public function testSetLastCardRankExisting()
    {
        $this->hand->draw($this->deckStub);
        $this->assertTrue($this->hand->setLastCardRank(10));
    }



    /**
     * Try to set last card with empty hand
     */
    public function testSetLastCardRankEmpty()
    {
        $this->assertFalse($this->hand->setLastCardRank(10));
    }
}
