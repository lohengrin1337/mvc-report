<?php

namespace App\PokerSquares;

use InvalidArgumentException;

/**
 * Container and manager for a number of poker square games
 */
class GameManager
{
    /**
     * @var PokerSquaresGame[] $games
     */
    private array $games = [];

    /**
     * Constructor
     * Populate $games
     * 
     * @param PokerSquaresGame[] $games
     * @throws InvalidArgumentException - if any game is invalid
     */
    public function __construct(array $games)
    {
        foreach ($games as $game) {
            if (!($game instanceof PokerSquaresGame)) {
                throw new InvalidArgumentException("Invalid game instance!");
            }
            $this->games[] = $game;

        }
    }



    /**
     * Get first unfinished game
     * 
     * @return PokerSquaresGame
     */
    public function getCurrentGame(): PokerSquaresGame
    {
        foreach ($this->games as $game) {
            if ($game->gameisOver()) {
                continue;
            }
            
            return $game;
        }
    }
}
