<?php

namespace App\PokerSquares;

interface ScoreMappingInterface
{
    /**
     * @param string $rule
     * @return int - points
     */
    public function getScore(string $rule): int;
}
