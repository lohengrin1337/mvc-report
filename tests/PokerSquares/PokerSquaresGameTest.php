<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\Card\CardInterface;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Score;
use PHPUnit\Framework\TestCase;
use \ReflectionClass;

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

        // create gameboard-mock, and connect to empty boardView, and bool values
        $this->gameboardMock = $this->createStub(Gameboard::class);
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
            $this->boardView[$i . $j] = null;
            }
        }
        $this->gameboardMock->method("getBoardView")->willReturnCallback(function() {
            return $this->boardView;
        });
        $this->gameboardMock->method("getBoard")->willReturnCallback(function() {
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

        // create card stub - 5 of hearts
        $cardStub = $this->createStub(CardInterface::class);
        $cardStub->method("getRank")->willReturn(5);
        $cardStub->method("getSuit")->willReturn("hearts");
        $cardStub->method("getAsString")->willReturn("svg-card-5h");

        // create deck-stub and connect with cardStub
        $deckStub = $this->createStub(CardDeck::class);
        $deckStub->method("getCardBack")->willReturn("svg-card-back");
        $deckStub->method("draw")->willReturnCallback(function() use ($cardStub) {
            return $cardStub;
        });
        $deckStub->method("peak")->willReturnCallback(function() use ($cardStub) {
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
     * Helper method to set a private or protected property
     *
     * @param object $object - The object to modify
     * @param string $property - The name of the property to set
     * @param mixed $value - The value to set the property to
     */
    private function setPrivateProperty(object $object, string $property, $value): void
    {
        $reflection = new ReflectionClass($object);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($object, $value);
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
     * get and verify RoundData before the start of the game
     */
    public function testGetRoundDataBeforeGame(): void
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
    }



    /**
     * check duration when in middle of game
     */
    public function testGetDurationInGame(): void
    {
        $threeMinutesAgo = new \DateTime('now -3 minutes', new \DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        $now = new \DateTime('now', new \DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        $diff = $now->diff($threeMinutesAgo);
        $expectedDuration = (new \DateTime())
            ->setTime($diff->h, $diff->i, $diff->s)
            ->format('H:i:s');

        // Set start time
        $this->setPrivateProperty($this->humanGame, 'start', $threeMinutesAgo);

        // Assert that the duration matches the expected duration
        $duration = $this->humanGame->getDuration()
            ->format('H:i:s');
        $this->assertEquals($expectedDuration, $duration);
    }



    /**
     * check duration at end of game
     */
    public function testGetDurationAtEndOfGame(): void
    {
        $start = new \DateTime('now -5 minutes', new \DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        $finish = new \DateTime('now -2 minutes', new \DateTimeZone(PokerSquaresGame::DEFAULT_TIME_ZONE));
        $diff = $finish->diff($start);
        $expectedDuration = (new \DateTime())
            ->setTime($diff->h, $diff->i, $diff->s)
            ->format('H:i:s');

        // Set start time
        $this->setPrivateProperty($this->humanGame, 'start', $start);
        $this->setPrivateProperty($this->humanGame, 'finish', $finish);

        // Assert that the duration matches the expected duration
        $duration = $this->humanGame->getDuration()
            ->format('H:i:s');
        $this->assertEquals($expectedDuration, $duration);
    }



    /**
     * test cpuPlay, and verify method calls
     */
    public function testCpuPlay(): void
    {
        // expect method calls
        $this->gameboardMock->expects($this->once())
            ->method("getBoard");
        $this->gameboardMock->expects($this->once())
            ->method("placeCard")
            ->with(
                $this->equalTo("11"),   // expected slot 11
                $this->callback(function ($card) {
                    return $card instanceof CardInterface;
                })
            );

        $this->cpuGame->cpuPlay();
    }
}
