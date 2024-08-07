<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    private Card $card;

    protected function setUp(): void
    {
        $this->card = new Card("spades", 10); // 10 of spades
    }



    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardValidArgs(): void
    {
        $card = new Card("hearts", 5); // 5 of hearts

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals("hearts", $card->getSuit());
        $this->assertEquals(5, $card->getRank());
    }



    /**
     * Construct object and verify that the object cannot be created
     * with invalid arg $suit
     */
    public function testCreateCardInvalidSuit(): void
    {
        $this->expectException("InvalidArgumentException");
        new Card("invalid", 5); // 5 of invalid
    }



    /**
     * Construct object and verify that the object cannot be created
     * with invalid arg $rank
     */
    public function testCreateCardInvalidRanks(): void
    {
        $this->expectException("InvalidArgumentException");
        new Card("hearts", 15); // 15 of hearts
    }



    /**
     * Get cardback and assert correct
     */
    public function testCorrectCardBack(): void
    {
        $res = $this->card->getCardBack();
        $this->assertEquals("[*]", $res);
    }



    /**
     * Set valid rank, assert true, and assert new rank
     */
    public function testSetValidRank(): void
    {
        $this->assertTrue($this->card->setRank(14)); // rank of ace
        $res = $this->card->getRank();
        $this->assertEquals(14, $res);
    }



    /**
     * Set invalid rank, assert false, check rank is 10
     */
    public function testSetInvalidRank(): void
    {
        $this->assertFalse($this->card->setRank(17)); // invalid
        $res = $this->card->getRank();
        $this->assertEquals(10, $res);
    }



    /**
     * Get string repr. of reg. rank (10) and assert correct
     */
    public function testCorrectStringDigit(): void
    {
        $res = $this->card->getAsString();
        $this->assertEquals("[♠10]", $res);
    }



    /**
     * Get string repr. face card and assert correct
     */
    public function testCorrectStringFace(): void
    {
        $this->card->setRank(12); // queen
        $res = $this->card->getAsString();
        $this->assertEquals("[♠Q]", $res);
    }
}
