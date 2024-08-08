<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\Card\CardInterface;
use App\Entity\Player;
use App\Entity\Score;
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
        $deckStub->method("getCardBack")->willReturn("svg-card-back");
        $deckStub->method("peak")->willReturnCallback(function () {
            $cardStub = $this->createStub(CardInterface::class);
            $cardStub->method("getRank")->willReturn(5);
            $cardStub->method("getSuit")->willReturn("hearts");
            $cardStub->method("getAsString")->willReturn("svg-card-5h");
            return $cardStub;
        });

        $player = new Player();
        $player->setName("Anonymous");

        $this->game = new PokerSquaresGame(
            new PokerSquareRules(),
            new AmericanScores(),
            new Score(),
            new Gameboard(),
            $player,
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



    /**
     * Verify initial state
     */
    public function testInitialState(): void
    {
        $state = $this->game->getState();

        $this->assertEquals("Anonymous", $state["player"]);
        $this->assertEquals("human", $state["playerType"]);
        $this->assertEquals("svg-card-back", $state["cardBack"]);
        $this->assertEquals("svg-card-5h", $state["card"]);
        $this->assertIsArray($state["board"]);
        $this->assertIsArray($state["handScores"]);
        $this->assertEquals(0, $state["totalScore"]);

        $this->assertFalse($this->game->gameIsOver());
    }



    /**
     * Place three cards in row 1 (three of a kind = 10p),
     * and two cards in col5 (two of a kind = 2p),
     * and verify score
     */
    public function testProcess(): void
    {
        $this->game->process("11");
        $this->game->process("12");
        $this->game->process("13");
        $this->game->process("45");
        $this->game->process("55");

        $state = $this->game->getState();
        $this->assertEquals(10, $state["handScores"]["row1"]);
        $this->assertEquals(2, $state["handScores"]["col5"]);
        $this->assertEquals(12, $state["totalScore"]);
    }
}
