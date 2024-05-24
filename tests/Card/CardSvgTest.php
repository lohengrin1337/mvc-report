<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardSvg
 */
class CardSvgTest extends TestCase
{
    private CardSvg $card;

    protected function setUp(): void
    {
        $this->card = new CardSvg("diamonds", 3); // 3 of diamonds
    }



    /**
     * Get cardback and assert correct
     */
    public function testCorrectCardBack(): void
    {
        $res = $this->card->getCardBack();
        $this->assertEquals("svg-card-back", $res);
    }



    /**
     * Get string repr. of 3 of diamonds
     */
    public function testStringRepr(): void
    {
        $res = $this->card->getAsString();
        $this->assertEquals("svg-card-3d", $res);
    }



    /**
     * Change rank and get string repr. of king of diamonds
     */
    public function testStringReprAfterUpdate(): void
    {
        $this->card->setRank(13); // king
        $res = $this->card->getAsString();
        $this->assertEquals("svg-card-kd", $res);
    }
}
