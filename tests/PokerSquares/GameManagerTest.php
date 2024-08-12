<?php

namespace App\PokerSquares;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameManager.
 */
class GameManagerTest extends TestCase
{
    private GameManager $gameManager;
    private PokerSquaresGame $gameMock1;
    private PokerSquaresGame $gameMock2;
    private bool $gameIsOver1;
    private bool $gameIsOver2;
    private array $state1;
    private array $state2;

    protected function setUp(): void
    {
        $this->gameIsOver1 = true;
        $this->gameIsOver2 = false;
        $this->state1 = [
            "player" => "player1",
            "totalScore" => 20
        ];
        $this->state2 = [
            "player" => "player2",
            "totalScore" => 25
        ];

        // create new game manager with two game-mocks (finished and unfinished, and different states)
        $this->gameMock1 = $this->createMock(PokerSquaresGame::class);
        $this->gameMock1->method("gameIsOver")->willReturnCallback(function () {
            return $this->gameIsOver1;
        });
        $this->gameMock1->method("getState")->willReturnCallback(function () {
            return $this->state1;
        });

        $this->gameMock2 = $this->createMock(PokerSquaresGame::class);
        $this->gameMock2->method("gameIsOver")->willReturnCallback(function () {
            return $this->gameIsOver2;
        });
        $this->gameMock2->method("getState")->willReturnCallback(function () {
            return $this->state2;
        });

        $this->gameManager = new GameManager([$this->gameMock1, $this->gameMock2]);
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(GameManager::class, $this->gameManager);
    }



    /**
     * Construct with invalid arg, and expect exception
     */
    public function testCreateInvalidInstance(): void
    {
        $this->expectException("InvalidArgumentException");
        new GameManager([1,2]);
    }



    /**
     * get game by index
     */
    public function testGetGameByIndex(): void
    {
        $res = $this->gameManager->getGameByIndex(1);
        $this->assertInstanceOf(PokerSquaresGame::class, $res);
    }



    /**
     * get no game by invalid index - and verify null
     */
    public function testGetNoGameByIndex(): void
    {
        $res = $this->gameManager->getGameByIndex(2);
        $this->assertNull($res);
    }



    /**
     * get current game
     */
    public function testGetCurrentGame(): void
    {
        $res = $this->gameManager->getCurrentGame();
        $this->assertSame($this->gameMock2, $res);
    }



    /**
     * get no current game
     */
    public function testGetNoCurrentGame(): void
    {
        $this->gameIsOver2 = true; // mock2 behaves as finished
        $res = $this->gameManager->getCurrentGame();
        $this->assertNull($res);
    }



    /**
     * all games are not over
     */
    public function testAllGamesAreNotOver(): void
    {
        $this->assertFalse($this->gameManager->AllGamesAreOver());
    }



    /**
     * all games are over
     */
    public function testAllGamesAreOver(): void
    {
        $this->gameIsOver2 = true; // mock2 behaves as finished
        $this->assertTrue($this->gameManager->AllGamesAreOver());
    }



    /**
     * all get all game states
     */
    public function testGetAllGameStates(): void
    {
        $res = $this->gameManager->getAllGameStates();
        $this->assertContainsOnly("array", $res);
    }



    /**
     * get conclusion for 3 differrent senarios
     */
    public function testGetConclusion(): void
    {
        // both games are over, player2 is winner
        $this->gameIsOver2 = true;

        $res = $this->gameManager->getConclusion();
        $this->assertEquals("Bra kämpat player2! - 25 poäng", $res);

        // player1 is winner
        $this->state1["totalScore"] = 50;
        $res = $this->gameManager->getConclusion();
        $this->assertEquals("Snyggt jobbat player1! - 50 poäng", $res);

        // player 2 is winner
        $this->state2["totalScore"] = 110;
        $res = $this->gameManager->getConclusion();
        $this->assertEquals("Imponerande player2! - 110 poäng", $res);

        // player 1 and 2 are winners
        $this->state1["totalScore"] = 110;
        $res = $this->gameManager->getConclusion();
        $this->assertEquals("Imponerande player1 och player2! - 110 poäng", $res);
    }
}
