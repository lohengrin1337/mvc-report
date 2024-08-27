<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use App\Trait\Nameable;

/**
 * Class for full house rule
 */
class FullHouse implements PokerRuleInterface
{
    use Nameable;
    use RankMappingTrait;

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("full-house");
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

        // check condition of rule (at least two of a rank once and three of a rank once)
        $ruleIsMet = false;
        $pairCount = 0;
        $threeOrMoreCount = 0;
        foreach ($rankMap as $count) {
            $pairCount += ($count === 2) ? 1 : 0;
            $threeOrMoreCount += ($count >= 3) ? 1 : 0;
        }

        if (
            ($pairCount && $threeOrMoreCount)
            || $threeOrMoreCount >= 2
        ) {
            $ruleIsMet = true;
        }

        return $ruleIsMet;
    }
}
