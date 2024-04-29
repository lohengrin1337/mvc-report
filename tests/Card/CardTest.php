<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    private Card $card;

    private function setUp(): void
    {
        $this->card = new Card("spades", 10); // 10 of spades
    }



    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardValidArgs()
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
    public function testCreateCardInvalidSuit()
    {
        $this->expectException("InvalidArgumentException");
        $card = new Card("invalid", 5); // 5 of invalid
    }



    /**
     * Construct object and verify that the object cannot be created
     * with invalid arg $rank
     */
    public function testCreateCardInvalidRanks()
    {
        $this->expectException("InvalidArgumentException");
        $card = new Card("hearts", 15); // 15 of hearts
    }



    /**
     * Get cardback and assert correct
     */
    public function testCorrectCardBack()
    {
        $res = $this->card->getCardBack();
        $this->assertEquals("[*]", $res);
    }



    /**
     * Set valid rank, assert true, and assert new rank
     */
    public function testSetValidRank()
    {
        $this->assertTrue($this->card->setRank(14)); // rank of ace
        $res = $this->card->getRank();
        $this->assertEquals(14, $res);
    }



    /**
     * Set invalid rank, assert false, check rank is 10
     */
    public function testSetInvalidRank()
    {
        $this->assertFalse($this->card->setRank(17)); // invalid
        $res = $this->card->getRank();
        $this->assertEquals(10, $res);
    }



    /**
     * Get string repr. of reg. rank (10) and assert correct
     */
    public function testCorrectStringDigit()
    {
        $res = $this->card->getAsString();
        $this->assertEquals("[♠10]", $res);
    }



    /**
     * Get string repr. face card and assert correct
     */
    public function testCorrectStringFace()
    {
        $this->card->setRank(12); // queen
        $res = $this->card->getAsString();
        $this->assertEquals("[♠Q]", $res);
    }
}