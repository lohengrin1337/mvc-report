<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGame21Test.
 */
class CardGame21Test extends TestCase
{
    // private CardGame21 $game;

    // private CardDeck $deckStub;

    // private CardHand $handStub;

    // protected function setUp(): void
    // {
    //     $handStub = $this->createStub(CardHand::class);
    //     // $handStub->method('getSuit')->willReturn('hearts');

    //     $deckStub = $this->createStub(CardDeck::class);
    //     $deckStub->method('draw')->willReturn($cardStub);
    //     $this->deckStub = $deckStub;
    // }



    /**
     * Create game and assert instance and properties
     */
    public function testCreateCardGame21()
    {
        $game = new CardGame21(
            new CardDeck(Card::class),
            new CardHand(),
            new CardHand()
        );

        $this->assertInstanceOf(CardGame21::class, $game);
    }
}
