<?php

namespace App\PokerSquares;

use InvalidArgumentException;

/**
 * Container and manager for a set of poker square games
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
     * Get game by index
     * 
     * @param int $index
     * @return PokerSquaresGame|null
     */
    public function getGameByIndex($index): PokerSquaresGame|null
    {
        if (!isset($this->games[$index])) {
            return null;
        }
        return $this->games[$index];
    }



    /**
     * Get the current (first unfinished) game
     * 
     * @return PokerSquaresGame|null
     */
    public function getCurrentGame(): PokerSquaresGame|null
    {
        foreach ($this->games as $game) {
            if ($game->gameisOver()) {
                continue;
            }
            return $game;
        }
        return null;
    }



    /**
     * Check if all games are finished
     * 
     * @return bool
     */
    public function allGamesAreOver(): bool
    {
        return !$this->getCurrentGame();
    }



    /**
     * Get state of all games
     * 
     * @return array<array<mixed>>
     */
    public function getAllGameStates(): array
    {
        return array_map(function ($game) {
            return $game->getState();
        }, $this->games);
    }



    /**
     * Assess quality of score
     * 
     * @param int $score
     * @return string - assassment
     */
    private function assessScore($score): string
    {
        if ($score < 30) {
            return "Bra kämpat";
        }
        if ($score < 100) {
            return "Snyggt jobbat";
        }
        return "Imponerande";
    }



    /**
     * Get concluding message with winner and score
     * 
     * @return string
     */
    public function getConclusion(): string
    {
        $winner = "";
        $winningScore = -1;

        foreach ($this->games as $game) {
            $state = $game->getState();
            $score = $state["totalScore"];
            $name = $state["player"];

            if ($score > $winningScore) {
                $winningScore = $score;
                $winner = $state["player"];
            } elseif ($score === $winningScore) {
                $winner .= " och " . $state["player"];
            }
        }

        $assassment = $this->assessScore($winningScore);
        return "$assassment $winner! - $winningScore poäng";
    }
}
