<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    private CardGraphic $card;

    protected function setUp(): void
    {
        $this->card = new CardGraphic("diamonds", 3); // 3 of diamonds
    }



    /**
     * Get cardback and assert correct
     */
    public function testCorrectCardBack()
    {
        $res = $this->card->getCardBack();
        $this->assertEquals("&#x1f0a0", $res);
    }



    /**
     * Get string repr. of reg. rank (3) and assert correct
     */
    public function testCorrectStringDigit()
    {
        $res = $this->card->getAsString();
        $this->assertEquals("&#x1f0c3", $res);
    }



    /**
     * Get string repr. face card and assert correct
     */
    public function testCorrectStringFace()
    {
        $this->card->setRank(14); // ace
        $res = $this->card->getAsString();
        $this->assertEquals("&#x1f0c1", $res);
    }
}