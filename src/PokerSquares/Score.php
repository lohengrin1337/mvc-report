<?php

namespace App\PokerSquares;

use InvalidArgumentException;

/**
 * Class holds score for Poker Squares
 */
class Score
{
    /**
     * @var array $score - score of the 10 hands
     */
    private array $score;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->score = [
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
    }



    /**
     * Get total score
     * 
     * @return int
     */
    public function getTotal(): int
    {
        return (int) array_sum($this->score);
    }



    /**
     * Get details on score for each hand
     * 
     * @return array
     */
    public function getDetails(): array
    {
        return $this->score;
    }



    /**
     * Set score for a single hand
     * 
     * @param string $handName
     * @param int $points
     * @return void
     */
    public function setHandScore(string $handName, int $points): void
    {
        if (!array_key_exists($handName, $this->score)) {
            throw new InvalidArgumentException("Invalid hand name!");
        }

        $this->score[$handName] = $points;
    }
}