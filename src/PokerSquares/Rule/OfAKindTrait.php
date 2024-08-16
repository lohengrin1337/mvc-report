<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;

trait OfAKindTrait
{
    use RankMappingTrait;

    /**
     * @var int $amount - the amount of a kind for the rule
     *                    is set in the constructor of the rule class
     */
    private int $amount;



    /**
     * Check if rule is met
     *
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool
    {
        // get count of unique ranks (rank => count)
        $rankMap = $this->getRankMap($cards);

        // check condition of rule
        $ruleIsMet = false;
        foreach ($rankMap as $count) {
            if ($count >= $this->amount) {
                $ruleIsMet = true;
            }
        }

        return $ruleIsMet;
    }



    // /**
    //  * @param CardInterface[] $cards - array of cards to check
    //  * @return bool - true if rule is met
    //  */
    // public function checkHand(array $cards): bool
    // {
    //     // get the rank of all cards
    //     $ranks = array_map(
    //         function($card)
    //         {
    //             return $card->getRank();
    //         },
    //         $cards
    //     );

    //     // get count of unique ranks (rank => count)
    //     $uniqueRankCount = array_count_values($ranks);

    //     // check condition of rule
    //     $ruleIsMet = false;
    //     foreach ($uniqueRankCount as $count) {
    //         if ($count >= $this->amount) {
    //             $ruleIsMet = true;
    //         }
    //     }

    //     return $ruleIsMet;
    // }
}
