<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\Card\CardInterface;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Score;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerSquaresGame.
 */
class PokerSquaresGameTest extends TestCase
{
    private PokerSquaresGame $humanGame;
    private PokerSquaresGame $cpuGame;
    private Score $scoreMock;
    private Gameboard $gameboardMock;
    private array $hands = [];
    private int $totalScore;
    private array $boardView = [];
    private bool $boolVal1;
    private bool $boolVal2;

    protected function setUp(): void
    {
        // create rules-stub - assessing every hand to one-pair
        $rulesStub = $this->createStub(RuleCollectionInterface::class);
        $rulesStub->method("assessHand")->willReturn("one-pair");

        // create score map-stub - allways returns 2 points
        $scoreMapStub = $this->createStub(ScoreMappingInterface::class);
        $scoreMapStub->method("getScore")->willReturn(2);


        // create score-mock, and connect to hands and totalScore
        $this->scoreMock = $this->createMock(Score::class);
        $this->hands = [
            "row1" => 0,
            "row2" => 0,
            "row3" => 0,
            "row4" => 0,
            "row5" => 0,
            "col1" => 0,
            "col2" => 0,
            "col3" => 0,
            "col4" => 0,
            "col5" => 0,
        ];
        $this->scoreMock->method("getHands")->willReturnCallback(function() {
            return $this->hands;
        });
        $this->totalScore = 0;
        $this->scoreMock->method("getTotal")->willReturnCallback(function() {
            return $this->totalScore;
        });

        // create gameboard-stub, and connect to empty boardView, and bool values
        $this->gameboardMock = $this->createStub(Gameboard::class);
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
            $this->boardView[$i . $j] = null;
            }
        }
        $this->gameboardMock->method("getBoardView")->willReturnCallback(function() {
            return $this->boardView;
        });
        $this->boolVal1 = false;
        $this->gameboardMock->method("boardHasOneCard")->willReturnCallback(function() {
            return $this->boolVal1;
        });
        $this->boolVal2 = false;
        $this->gameboardMock->method("boardIsFull")->willReturnCallback(function() {
            return $this->boolVal2;
        });
        $this->gameboardMock->method("getAllHands")->willReturnCallback(function() {
            $cardStub = $this->createStub(CardInterface::class);
            return array_map(function($card) use ($cardStub) {
                return [$cardStub, $cardStub, $cardStub, $cardStub, $cardStub];
            }, $this->hands);
        });

        // create stubs for deck and cards
        $deckStub = $this->createStub(CardDeck::class);
        $deckStub->method("getCardBack")->willReturn("svg-card-back");
        $deckStub->method("draw")->willReturnCallback(function () {
            $cardStub = $this->createStub(CardInterface::class);
            $cardStub->method("getRank")->willReturn(5);
            $cardStub->method("getSuit")->willReturn("hearts");
            return $cardStub;
        });
        $deckStub->method("peak")->willReturnCallback(function () {
            $cardStub = $this->createStub(CardInterface::class);
            $cardStub->method("getRank")->willReturn(5);
            $cardStub->method("getSuit")->willReturn("hearts");
            $cardStub->method("getAsString")->willReturn("svg-card-5h");
            return $cardStub;
        });

        // create human player-stub
        $humanPlayerStub = $this->createStub(Player::class);
        $humanPlayerStub->method("getName")->willReturn("Human Player");
        $humanPlayerStub->method("getType")->willReturn("human");

        // init a new game with stubs - human player
        $this->humanGame = new PokerSquaresGame(
            $rulesStub,
            $scoreMapStub,
            $this->scoreMock,
            $this->gameboardMock,
            $humanPlayerStub,
            $deckStub
        );

        // create cpu player-stub
        $cpuPlayerStub = $this->createStub(Player::class);
        $cpuPlayerStub->method("getName")->willReturn("CPU Player");
        $cpuPlayerStub->method("getType")->willReturn("cpu");
        $cpuPlayerStub->method("getLevel")->willReturn(3);

        // init a new game with stubs - cpu player
        $this->cpuGame = new PokerSquaresGame(
            $rulesStub,
            $scoreMapStub,
            $this->scoreMock,
            $this->gameboardMock,
            $cpuPlayerStub,
            $deckStub
        );
    }



    /**
     * Construct object and verify instances
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(PokerSquaresGame::class, $this->humanGame);
        $this->assertInstanceOf(PokerSquaresGame::class, $this->cpuGame);
    }



    /**
     * Verify initial state
     */
    public function testInitialState(): void
    {
        $state = $this->humanGame->getState();

        $this->assertEquals("Human Player", $state["player"]);
        $this->assertEquals("human", $state["playerType"]);
        $this->assertEquals("svg-card-back", $state["cardBack"]);
        $this->assertEquals("svg-card-5h", $state["card"]);
        $this->assertIsArray($state["board"]);
        $this->assertIsArray($state["handScores"]);
        $this->assertEquals(0, $state["totalScore"]);
    }



    /**
     * get and verify RoundData unfinished game
     */
    public function testGetRoundDataUnfinished(): void
    {
        $data = $this->humanGame->getRoundData();

        $this->assertInstanceOf(Player::class, $data["player"]);
        $this->assertInstanceOf(Board::class, $data["board"]);
        $this->assertInstanceOf(Score::class, $data["score"]);
        $this->assertNull($data["start"]);
        $this->assertNull($data["finish"]);
        $this->assertEquals('00:00:00', $data["duration"]->format('H:i:s'));
    }



    /**
     * Game is not over
     */
    public function testGameIsNotOver(): void
    {
        $this->gameboardMock->expects($this->once())
            ->method("boardIsFull");

        $this->assertFalse($this->humanGame->gameIsOver());
    }



    /**
     * Process some card placements and verify function calls
     */
    public function testProcess(): void
    {
        // expect method calls
        $this->gameboardMock->expects($this->exactly(2))
            ->method("placeCard");
        $this->gameboardMock->expects($this->exactly(2))
            ->method("boardHasOneCard");
        $this->gameboardMock->expects($this->exactly(2))
            ->method("boardIsFull");
        $this->scoreMock->expects($this->exactly(20))
            ->method("setHandScore");

        // simulate board has one card
        $this->boolVal1 = true;
        $this->humanGame->process("11");

        // simulate board is full
        $this->boolVal1 = false;
        $this->boolVal2 = true;
        $this->humanGame->process("55");

        // // check start and finish
        // $data = $this->humanGame->getRoundData();
        // $this->isInstanceOf("DateType", $data["start"]);
        // $this->isInstanceOf("DateType", $data["finish"]);
        // $this->isInstanceOf("DateType", $data["duration"]);
        // $this->assertNotSame($data["start"], $data["finish"]);
    }



    /**
     * check duration when in middle of game
     */
    public function testGetDurationInGame(): void
    {
        // Set start time
        $start = new \DateTime('2023-08-10 12:00:00', new \DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        $this->setPrivateProperty($this->pokerSquaresGame, 'start', $start);

        // // Set finish time
        // $finish = new DateTime('2023-08-10 12:05:30', new DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        // $this->setPrivateProperty($this->pokerSquaresGame, 'finish', $finish);

        // Calculate expected duration
        $expectedDuration = '00:05:30';

        // Assert that the duration matches the expected duration
        $duration = $this->pokerSquaresGame->getDuration();
        $this->assertEquals($expectedDuration, $duration->format('H:i:s'));
    }


//     public function testProcess2()
// {
//     $slot = '11'; // Replace with an appropriate slot value

//     // Mock the CardDeck to return a specific card
//     $cardMock = $this->createMock(CardInterface::class);
//     $this->deckMock->method('draw')->willReturn($cardMock);

//     // Mock the GameBoard to expect specific interactions
//     $this->gameboardMock->expects($this->once())
//         ->method('placeCard')
//         ->with($this->equalTo($slot), $this->equalTo($cardMock));

//     // Trigger the process method
//     $this->pokerSquaresGame->process($slot);

//     // You can also add assertions for start/finish times if needed
// }

    


    
    // /**
    //  * get and verify RoundData finished game
    //  */
    // public function testGetRoundDataFinished(): void
    // {
    //     // foreach (array_keys($this->boardView) as $slot) {
    //         //     $this->boardView[$slot] = $this->createStub(CardInterface::class);
    //         // }
    //         // 
        
    //     // fill board 

    //     $data = $this->humanGame->getRoundData();

    //     var_dump($data);

    //     $this->assertInstanceOf(Player::class, $data["player"]);
    //     $this->assertInstanceOf(Board::class, $data["board"]);
    //     $this->assertInstanceOf(Score::class, $data["score"]);
    //     $this->assertInstanceOf("DateTime", $data["start"]);
    //     $this->assertInstanceOf("DateTime", $data["finish"]);
    //     $this->assertInstanceOf("DateTime", $data["duration"]);
    // }


    // /**
    //  * 
    //  */
    // public function testProcess(): void
    // {
    //     $this->game->process("11");
    //     $this->game->process("12");
    //     $this->game->process("13");
    //     $this->game->process("45");
    //     $this->game->process("55");

    //     $state = $this->game->getState();
    //     $this->assertEquals(10, $state["handScores"]["row1"]);
    //     $this->assertEquals(2, $state["handScores"]["col5"]);
    //     $this->assertEquals(12, $state["totalScore"]);
    // }




}
