<?php

namespace App\PokerSquares;

use InvalidArgumentException;

/**
 * Class that maps a rule name to points
 * according to the American point system of Poker Squares
 */
class AmericanScores implements ScoreMappingInterface
{
    /**
     * @var array SCORE_MAP
     */
    private const SCORE_MAP = [
        "royal-flush" => 100,
        "straight-flush" => 75,
        "four-of-a-kind" => 50,
        "full-house" => 25,
        "flush" => 20,
        "straight" => 15,
        "three-of-a-kind" => 10,
        "two-pairs" => 5,
        "one-pair" => 2,
        "high-card" => 0,
        "no-cards" => 0,
    ];



    /**
     * Get points for a fullfilled rule
     * 
     * @param string $rule - name of a rule that is fullfilled
     * @throws InvalidArgumentException - if rule name is invalid
     * @return int - points
     */
    public function getScore(string $rule): int
    {
        if (!array_key_exists($rule, self::SCORE_MAP)) {
            throw new InvalidArgumentException("Invalid rule name!");
        }

        return self::SCORE_MAP[$rule];
    }
}
