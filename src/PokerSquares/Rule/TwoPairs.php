<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for two pairs rule
 */
class TwoPairs implements PokerRuleInterface
{
    use Nameable;
    use RankMappingTrait;

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("two-pairs");
    }



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

        // check condition of rule (at least two of a rank twice)
        $ruleIsMet = false;
        $pairCount = 0;
        foreach ($rankMap as $count) {
            if ($count >= 2) {
                $pairCount += 1;
            } 
        }

        if ($pairCount >= 2) {
            $ruleIsMet = true;
        }

        return $ruleIsMet;
    }
}