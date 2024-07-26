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
        return $this->games[$index];
    }



    /**
     * Get the current (first unfinished) game
     * 
     * @return PokerSquaresGame|null
     */
    private function getCurrentGame(): PokerSquaresGame|null
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
     * Get state of current game (first unfinished game)
     * 
     * @return array<mixed>|null
     */
    public function getCurrentGameState(): array|null
    {
        $game = $this->getCurrentGame();
        if ($game) {
            return $game->getState();
        }
        return null;
    }



    /**
     * Get state of all games
     * 
     * @return array<array<mixed>>|null
     */
    public function getAllGameStates(): array|null
    {
        $gameStates = [];
        foreach ($this->games as $game) {
            $gameStates[] = $game->getState();
        }
        return $gameStates;
    }



    /**
     * Process card placement, time, and scores for current game
     * 
     * @param string $slot - a valid card slot
     * @return void
     */
    public function processCurrent(string $slot): void
    {
        $game = $this->getCurrentGame();
        if ($game) {
            $game->process($slot);
        }
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
