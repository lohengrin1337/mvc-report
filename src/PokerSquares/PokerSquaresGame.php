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




}
