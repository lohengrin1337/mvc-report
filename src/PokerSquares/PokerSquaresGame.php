<?php

namespace App\PokerSquares;

use App\Card\CardDeck;
use App\PokerSquares\Rule\PokerRuleInterface;

class PokerSquaresGame
{
    /**
     * @var PokerRuleInterface[] $rules - set of rules
     */
    private array $rules;

    /**
     * @var GameBoard $gameboard
     */
    private GameBoard $gameboard;

    /**
     * @var Player $players
     */
    private Player $player;

    /**
     * @var CardDeck $deck
     */
    private CardDeck $deck;

    /**
     * Constructor
     * Set rules, gamebord, player and deck
     * 
     * @param PokerRuleInterface[] $rules - set of rules
     * @param GameBoard $gameboard
     * @param Player $player
     * @param CardDeck $deck
     */
    public function __construct(
        array $rules,
        GameBoard $gameboard,
        Player $player,
        CardDeck $deck
    ){
        $this->$rules = $rules;
        $this->$gameboard = $gameboard;
        $this->$player = $player;
        $this->$deck = $deck;
    }




}
