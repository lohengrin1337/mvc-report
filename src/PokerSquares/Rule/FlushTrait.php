<?php

namespace App\PokerSquares\Rule;

/**
 * Has the ability to check if flush rule is met
 */
trait FlushTrait
{
    /**
     * Get the card suits
     * 
     * @param CardInterface[] $cards
     * @return string[]
     */
    private function getSuits(array $cards): array
    {
        // get the suit of all cards
        $suits = array_map(
            function($card)
            {
                return $card->getSuit();
            },
            $cards
        );

        return $suits;
    }



    /**
     * Get a key => value array with the suit as key, and the count as value
     * 
     * @param CardInterface[] $cards
     * @return int[]
     */
    private function getSuitMap(array $cards): array
    {
        // return count of unique suits (suit => count)
        return array_count_values($this->getSuits($cards));
    }



    /**
     * Check if there is at least 5 cards of the same suit
     * 
     * @param CardInterface[] $cards
     * @return bool
     */
    private function isAFlush(array $cards): bool
    {
        $suitMap = $this->getSuitMap($cards);

        foreach ($suitMap as $count) {
            if ($count >= 5) {
                return true;
            }
        }

        return false;
    }
}