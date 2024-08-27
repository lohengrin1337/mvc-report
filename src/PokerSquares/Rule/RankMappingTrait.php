<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;

/**
 * Has the ability to map the count of cards with the same rank
 */
trait RankMappingTrait
{
    /**
     * Get the card ranks
     *
     * @param CardInterface[] $cards
     * @return int[]
     */
    private function getRanks(array $cards): array
    {
        // get the rank of all cards
        $ranks = array_map(function ($card) {
            return $card->getRank();
        }, $cards);

        return $ranks;
    }



    /**
     * Get a key => value array with the rank as key, and the count as value
     *
     * @param CardInterface[] $cards
     * @return int[]
     */
    private function getRankMap(array $cards): array
    {
        // return count of unique ranks (rank => count)
        return array_count_values($this->getRanks($cards));
    }
}
