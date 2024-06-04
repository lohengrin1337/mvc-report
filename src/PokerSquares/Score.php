<?php

namespace App\PokerSquares;

use InvalidArgumentException;

/**
 * Class holds score for Poker Squares
 */
class Score
{
    /**
     * @var array $hands - score of the 10 hands
     */
    private array $hands;

    /**
     * @var int $total - total score
     */
    private int $total;

    /**
     * Constructor
     */
    public function __construct()
    {
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

        $this->total = 0;
    }



    /**
     * Get total score
     * 
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }



    /**
     * Get score for each hand
     * 
     * @return array
     */
    public function getHands(): array
    {
        return $this->hands;
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
        if (!array_key_exists($handName, $this->hands)) {
            throw new InvalidArgumentException("Invalid hand name!");
        }

        $this->hands[$handName] = $points;
        $this->updateTotal();
    }



    /**
     * Update total score
     * 
     * @return void
     */
    private function updateTotal(): void
    {
        $this->total = (int) array_sum($this->hands);
    }
}