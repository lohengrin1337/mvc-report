<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardDeck.
 */
class CardDeckTest extends TestCase
{
    private CardDeck $deck;

    protected function setUp(): void
    {
        // deck of CardGraphic cards
        $this->deck = new CardDeck(CardGraphic::class);
    }



    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardDeck(): void
    {
        $this->assertInstanceOf(CardDeck::class, $this->deck);
        $this->assertIsString($this->deck->getCardBack());
        $this->assertEquals(52, $this->deck->getCount());
    }



    /**
     * Construct object with invalid arg, expect exception
     */
    public function testCreateCardDeckInvalid(): void
    {
        $this->expectException("InvalidArgumentException");
        new CardDeck("NotACardInterface::class");
    }



    /**
     * Get cards as string[], and assert count 52, and
     */
    public function testGetAsString(): void
    {
        $res = $this->deck->getAsString();
        $this->assertCount(52, $res);
        $this->assertContainsOnly("string", $res);
    }



    /**
     * Sorted deck is not equal to shuffled deck,
     * and two shuffled decks are not equal,
     * but two sorted decks are equal
     */
    public function testSortAndShuffle(): void
    {
        $this->deck->shuffle();
        $shuffled1 = $this->deck->getAsString();
        $this->deck->sort();
        $sorted1 = $this->deck->getAsString();
        $this->deck->shuffle();
        $shuffled2 = $this->deck->getAsString();
        $this->deck->sort();
        $sorted2 = $this->deck->getAsString();

        $this->assertNotEquals($shuffled1, $sorted1);
        $this->assertNotEquals($shuffled1, $shuffled2);
        $this->assertEquals($sorted1, $sorted2);
    }



    /**
     * Sort deck, and draw. assert card is 13 of clubs
     */
    public function testDrawWhenSorted(): void
    {
        $this->deck->sort();
        $res = $this->deck->draw();

        $this->assertNotNull($res); // for safety
        $this->assertEquals("clubs", $res->getSuit());
        $this->assertEquals(13, $res->getRank());
    }



    /**
     * peak the top card (king of clubs)
     */
    public function testPeakSorted(): void
    {
        $deck = new CardDeck(CardSvg::class);
        $deck->sort();
        $res = $deck->peak();

        $this->assertEquals("svg-card-kc", $res->getAsString());
    }
}
