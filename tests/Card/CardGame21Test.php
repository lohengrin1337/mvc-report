<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGame21Test.
 */
class CardGame21Test extends TestCase
{
    private CardGame21 $game;

    private CardGame21 $gameWithDeckStub;

    private CardDeck $deckStub;

    private CardInterface $cardStub;


    // private CardDeck $deckStub;

    // private CardHand $handStub;

    protected function setUp(): void
    {
        // regular game
        $this->game = new CardGame21(
            new CardDeck(Card::class),
            new CardHand(),
            new CardHand()
        );

        $this->cardStub = $this->createStub(CardInterface::class);

        $this->deckStub = $this->createStub(CardDeck::class);
        $this->deckStub->method('draw')->willReturn($this->cardStub);

        $this->gameWithDeckStub = new CardGame21(
            $this->deckStub,
            new CardHand(),
            new CardHand()
        );
    }



    /**
     * Create game and assert instance and properties
     */
    public function testCreateCardGame21(): void
    {
        $this->assertInstanceOf(CardGame21::class, $this->game);
    }



    /**
     * Get state and assert is array
     */
    public function testInitialState(): void
    {
        $gameState = $this->game->getState();

        // assert getState returns array
        $this->assertIsArray($gameState);

        // assert playerHand and bankHand are empty
        $this->assertEmpty($gameState["playerHand"]);
        $this->assertEmpty($gameState["bankHand"]);

        // assert playerSum and bankSum are 0
        $this->assertEquals(0, $gameState["playerSum"]);
        $this->assertEquals(0, $gameState["bankSum"]);

        // assert flags are at correct initial value
        $this->assertFalse($gameState["lastCardIsAce"]);
        $this->assertFalse($gameState["gameOver"]);
        $this->assertNull($gameState["winner"]);
    }



    /**
     * Let player draw a card, and check state variables
     */
    public function testPlayerDrawsCard(): void
    {
        // player draws 1 card
        $this->game->draw();

        // get game state
        $gameState = $this->game->getState();

        // assert deckCount is 51
        $this->assertEquals(51, $gameState["deckCount"]);

        // assert playerHand is not empty
        $this->assertNotEmpty($gameState["playerHand"]);

        // assert playerSum > 0
        $this->assertGreaterThan(0, $gameState["playerSum"]);

        // assert gameOver is false
        $this->assertFalse($gameState["gameOver"]);
    }



    /**
     * Let player draw 3 cards (rank 8), and assert state variables
     */
    public function testPlayerLoosesAt24(): void
    {
        // set card stub to return the rank of a 8
        $this->cardStub->method('getRank')->willReturn(8);

        // player draws 3 cards (8 x 3 = 24);
        $this->gameWithDeckStub->draw();
        $this->gameWithDeckStub->draw();
        $this->gameWithDeckStub->draw();

        // get game state
        $gameState = $this->gameWithDeckStub->getState();

        // assert playerSum > 0
        $this->assertEquals(24, $gameState["playerSum"]);

        // assert gameOver is true
        $this->assertTrue($gameState["gameOver"]);

        // assert winner is bank
        $this->assertEquals("bank", $gameState["winner"]);
    }



    /**
     * Set ace rank when last card is ace
     */
    public function testSetAceRankValid(): void
    {
        // set card stub to return the rank of an ace
        $this->cardStub->method('getRank')->willReturn(1);
        $this->cardStub->method('setRank')->willReturn(true);

        // draw the ace
        $this->gameWithDeckStub->draw();

        // assert sum of ace (1), and successfull set new rank
        $this->assertEquals(1, $this->gameWithDeckStub->getState()["playerSum"]);
        $this->assertTrue($this->gameWithDeckStub->setAceRank(14));
    }



    /**
     * Set ace rank when last card is not ace
     */
    public function testSetAceRankInvalid(): void
    {
        // set card stub to return the rank of a 7
        $this->cardStub->method('getRank')->willReturn(7);
        $this->cardStub->method('setRank')->willReturn(true);

        // draw the 7
        $this->gameWithDeckStub->draw();

        // assert sum of 7 (7), and unsuccessfull set new rank
        $this->assertEquals(7, $this->gameWithDeckStub->getState()["playerSum"]);
        $this->assertFalse($this->gameWithDeckStub->setAceRank(14));
    }



    /**
     * Play bank with cardStub (rank 7), and assert results
     */
    public function testBankWinsAt21(): void
    {
        // set card stub to return the rank of a 7
        $this->cardStub->method('getRank')->willReturn(7);

        // player stops at one card (7);
        $this->gameWithDeckStub->draw();

        // bank plays until sum 21, then game ends through endGame()
        $this->gameWithDeckStub->playBank();

        $gameState = $this->gameWithDeckStub->getState();

        // assert playerSum is 7 and bankSum is 21
        $this->assertEquals(7, $gameState["playerSum"]);
        $this->assertEquals(21, $gameState["bankSum"]);

        // assert gameOver is true
        $this->assertTrue($gameState["gameOver"]);

        // assert winner is bank
        $this->assertEquals("bank", $gameState["winner"]);
    }



    /**
     * Play bank with cardStub (rank 9), and assert results
     */
    public function testBankWinsAt18(): void
    {
        // set card stub to return the rank of a 9
        $this->cardStub->method('getRank')->willReturn(9);

        // player stops at two cards (18);
        $this->gameWithDeckStub->draw();
        $this->gameWithDeckStub->draw();

        // bank plays until sum 18, then game ends through endGame()
        $this->gameWithDeckStub->playBank();

        $gameState = $this->gameWithDeckStub->getState();

        // assert playerSum is 18 and bankSum is 18
        $this->assertEquals(18, $gameState["playerSum"]);
        $this->assertEquals(18, $gameState["bankSum"]);

        // assert gameOver is true
        $this->assertTrue($gameState["gameOver"]);

        // assert winner is bank
        $this->assertEquals("bank", $gameState["winner"]);
    }



    /**
     * Play bank with cardStub (rank 8), and assert results
     */
    public function testBankLoosesAt24(): void
    {
        // set card stub to return the rank of a 8
        $this->cardStub->method('getRank')->willReturn(8);

        // player stops at one card (8);
        $this->gameWithDeckStub->draw();

        // bank plays until sum 24, then game ends through checkSum()
        $this->gameWithDeckStub->playBank();

        $gameState = $this->gameWithDeckStub->getState();

        // assert playerSum is 8 and bankSum is 24
        $this->assertEquals(8, $gameState["playerSum"]);
        $this->assertEquals(24, $gameState["bankSum"]);

        // assert gameOver is true
        $this->assertTrue($gameState["gameOver"]);

        // assert winner is player
        $this->assertEquals("spelare", $gameState["winner"]);
    }


    /**
     * Let player and bank draw cards (rank 3).
     * Player draws 7 = 21
     * Bank draws 6 = 18
     * Assert state variables
     */
    public function testPlayerWinsAt21(): void
    {
        // set card stub to return the rank of a 3
        $this->cardStub->method('getRank')->willReturn(3);

        // player draws 7 cards (3 x 7 = 21);
        for ($i = 0; $i < 7; $i++) {
            $this->gameWithDeckStub->draw();
        }

        // bank stops at 3 x 6 = 18
        $this->gameWithDeckStub->playBank();

        // get game state
        $gameState = $this->gameWithDeckStub->getState();

        // assert playerSum is 21 an bankSum is 18
        $this->assertEquals(21, $gameState["playerSum"]);
        $this->assertEquals(18, $gameState["bankSum"]);

        // assert gameOver is true
        $this->assertTrue($gameState["gameOver"]);

        // assert winner is player
        $this->assertEquals("spelare", $gameState["winner"]);
    }



    /**
     * Bank draws aces, and sets the first to 14
     */
    public function testBankChooseAceRank14(): void
    {
        // deckStub will return the same instance of Card on multiple draw()
        $deckStub = $this->createStub(CardDeck::class);
        $deckStub->method("draw")->willReturn(new Card("hearts", 1));

        $gameWithDeckStub = new CardGame21(
            $deckStub,
            new CardHand(),
            new CardHand()
        );


        $gameWithDeckStub->playBank();

        $gameState = $gameWithDeckStub->getState();

        // assert sum is 28 and count is 2
        $this->assertEquals(28, $gameState["bankSum"]);
        $this->assertCount(2, $gameState["bankHand"]);
    }
}
