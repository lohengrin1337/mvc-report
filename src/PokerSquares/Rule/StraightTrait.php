<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;

/**
 * Has the ability to check if straight rule is met
 */
trait StraightTrait
{
    use RankMappingTrait;

    /** @var array<array<int>> */
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
     * @param array<array<int>> $straights - straights to match
     * @return bool
     */
    private function isAStraight(
        array $cards,
        array $straights = self::VALID_STRAIGHTS
    ): bool {
        $ranks = $this->getRanks($cards);

        foreach ($straights as $straight) {
            if (!array_diff($straight, $ranks)) {
                return true;
            }
        }

        return false;
    }
}
