<?php

namespace App\PokerSquares\Rule;

/**
 * Has the ability to check if straight rule is met
 */
trait StraightTrait
{
    use RankMappingTrait;

    private const VALID_STRAIGHTS = [
        [1,2,3,4,5],
        [2,3,4,5,6],
        [3,4,5,6,7],
        [4,5,6,7,8],
        [5,6,7,8,9],
        [6,7,8,9,10],
        [7,8,9,10,11],
        [8,9,10,11,12],
        [9,10,11,12,13],
        [10,11,12,13,1],
        [11,12,13,1,2],
        [12,13,1,2,3],
        [13,1,2,3,4],
    ];

    /**
     * Check if there is at least 5 cards in sequence (ranks)
     * 
     * @param CardInterface[] $cards
     * @return bool
     */
    private function isAStraight(array $cards): bool
    {
        $ranks = $this->getRanks($cards);

        foreach ($self::VALID_STRAIGHTS as $straight) {
            if (!array_diff($straight, $ranks)) {
                return true;
            }
        }

        return false;
    }
}