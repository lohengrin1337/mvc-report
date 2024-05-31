<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerSquaresGame.
 */
class PokerSquaresGameTest extends TestCase
{
    private PokerSquaresGame $game;

    protected function setUp(): void
    {
        // $cardStub = $this->createStub(CardInterface::class);
        // $cardStub->method("getRank")->willReturn(5);
        // $cardStub->method("getSuit")->willReturn("hearts");

        $deckStub = $this->createStub(CardDeck::class);
        $deckStub->method("draw")->willReturnCallback(function () {
            $cardStub = $this->createStub(CardInterface::class);
            $cardStub->method("getRank")->willReturn(5);
            $cardStub->method("getSuit")->willReturn("hearts");
            return $cardStub;
        });

        $this->game = new PokerSquaresGame(
            new PokerSquareRules(),
            new AmericanScores(),
            new Score(),
            new Gameboard(),
            new Player("Anonymous"),
            $deckStub
        );
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(PokerSquaresGame::class, $this->game);
    }

}
