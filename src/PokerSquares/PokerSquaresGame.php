<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\Entity\Player;
use App\Entity\Score;
use App\PokerSquares\Cpu\CpuFactory;
use App\PokerSquares\Cpu\CpuLogicInterface;

/**
 * Game engine for Poker Squares
 * 
 * API:
 * 1. Draw a card
 * 2. Place a card
 * 3. Calculate points
 */
class PokerSquaresGame
{
    /**
     * @var string DEFAULT_TIME_ZONE
     */
    // public const DEFAULT_TIME_ZONE = "Europe/Stockholm";
    public const DEFAULT_TIME_ZONE = "UTC";

    /**
     * @var RuleCollectionInterface $rules - set of rules
     */
    private RuleCollectionInterface $rules;

    /**
     * @var ScoreMappingInterface $scoreMapper - maps a rule to a score
     */
    private ScoreMappingInterface $scoreMapper;

    /**
     * @var Score $score - holds the score for each hand
     */
    private Score $score;

    /**
     * @var GameBoard $gameboard - represents the 5x5 gameboard
     */
    private GameBoard $gameboard;

    /**
     * @var Player $player
     */
    private Player $player;

    /**
     * @var string|null $cpuIntel - brain of cpu player (class name)
     */
    private ?string $cpuIntel = null;

    /**
     * @var CardDeck $deck
     */
    private CardDeck $deck;

    /**
     * @var \DateTimeInterface|null $start - time of start
     */
    private ?\DateTimeInterface $start = null;

    /**
     * @var \DateTimeInterface|null $finish - time of finish
     */
    private ?\DateTimeInterface $finish = null;

    /**
     * Constructor
     * Set rules, score mapper, gamebord, player and deck
     * 
     * @param RuleCollectionInterface $rules - set of rules
     * @param ScoreMappingInterface $scoreMapper
     * @param Score $score
     * @param GameBoard $gameboard
     * @param Player $player
     * @param CardDeck $deck
     */
    public function __construct(
        RuleCollectionInterface $rules,
        ScoreMappingInterface $scoreMapper,
        Score $score,
        GameBoard $gameboard,
        Player $player,
        CardDeck $deck
    ){
        $this->rules = $rules;
        $this->scoreMapper = $scoreMapper;
        $this->score = $score;
        $this->gameboard = $gameboard;
        $this->player = $player;
        $this->deck = $deck;

        // Add cpu intel of some level
        if ($this->playerIsCpu()) {
            $level = $this->getCpuLevel();
            $this->cpuIntel = CpuFactory::getCpuIntel($level);
        }
    }



    /**
     * Get current state of the game
     * 
     * @return array<mixed>
     */
    public function getState(): array
    {
        return [
            "player" => $this->player->getName(),
            "playerType" => $this->player->getType(),
            "cardBack" => $this->deck->getCardBack(),
            "card" => $this->deck->peak()->getAsString(),
            "board" => $this->gameboard->getBoardView(),
            "handScores" => $this->score->getHands(),
            "totalScore" => $this->score->getTotal(),
        ];
    }



    /**
     * Get player, board and score to fill Round entity
     * 
     * @return array<mixed>
     */
    public function getRoundData(): array
    {
        return [
            "player" => $this->player,
            "board" => $this->gameboard->exportAsEntity(),
            "score" => $this->score,
            "start" => $this->start,
            "finish" => $this->finish,
            "duration" => $this->getDuration(),
        ];
    }



    /**
     * Check if game is over
     * 
     * @return bool
     */
    public function gameIsOver(): bool
    {
        return $this->gameboard->boardIsFull();
    }



    /**
     * Process new card placement, set start/finish time, and calculate scores
     * 
     * @param string $slot - a valid card slot
     * @return void
     */
    public function process(string $slot): void
    {
        $this->gameboard->placeCard($slot, $this->deck->draw());
        $this->setStartAndFinish();
        $this->calcScores();
    }



    /**
     * Calculate and set scores for each hand of the current gameboard
     * 
     * @return void
     */
    private function calcScores(): void
    {
        $hands = $this->gameboard->getAllHands();
        foreach ($hands as $handName => $hand) {
            $ruleName = $this->rules->assessHand($hand);        // get name of highest poker hand
            $points = $this->scoreMapper->getScore($ruleName);  // get points for matching rule
            $this->score->setHandScore($handName, $points);
        }
    }



    /**
     * Set time for start and finish (when first/last card is placed)
     * 
     * @return void
     */
    private function setStartAndFinish(): void
    {
        if ($this->gameboard->boardHasOneCard()) {
            $this->start = $this->getDateTime();
        }
        if ($this->gameboard->boardIsFull()) {
            $this->finish = $this->getDateTime();
        }
    }



    /**
     * Get current DateTime
     * 
     * @return \DateTimeInterface
     */
    private function getDateTime(): \DateTimeInterface
    {
        return new \DateTime('now', new \DateTimeZone(self::DEFAULT_TIME_ZONE));
        // return new \DateTime('now');
    }



    /**
     * Calculate duration from start to finish (or now if not finished)
     * 
     * @return \DateTimeInterface
     */
    public function getDuration(): \DateTimeInterface
    {
        $start = $this->start;
        $finish = $this->finish;

        if (!$start) {
            return (new \DateTime())->setTime(0, 0, 0);
        }
        if (!$finish) {
            $finish = $this->getDateTime();
        }

        $interval = $start->diff($finish);
        $duration = (new \DateTime())->setTime($interval->h, $interval->i, $interval->s);

        return $duration;
    }



    /**
     * Check if player is of typ cpu (computer player)
     * 
     * @return bool
     */
    public function playerIsCpu(): bool
    {
        return $this->player->getType() === "cpu";
    }



    /**
     * Get cpu level
     * 
     * @return int
     */
    private function getCpuLevel(): int
    {
        return $this->player->getLevel();
    }



    /**
     * Let cpu do a card placement
     * 
     * @return void
     */
    public function cpuPlay(): void
    {
        // use static method of cpu intel class
        $slot = $this->cpuIntel::suggestPlacement(
            $this->gameboard->getBoard(),
            $this->deck->peak()
        );

        $this->process($slot);
    }
}
