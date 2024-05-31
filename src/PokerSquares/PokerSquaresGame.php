<?php

namespace App\PokerSquares;

use App\Card\CardDeck;

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
     * @var CardDeck $deck
     */
    private CardDeck $deck;

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
            "cardBack" => $this->deck->getCardBack(),
            "card" => $this->deck->peak(),
            "board" => $this->gameboard->getBoardView(),
            "handScores" => $this->score->getDetails(),
            "totalScore" => $this->score->getTotal(),
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
     * Process new card placement and calculate scores
     * 
     * @param string $slot - a valid card slot
     * @return void
     */
    public function process(string $slot): void
    {
        $this->gameboard->placeCard($slot, $this->deck->draw());
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
}
