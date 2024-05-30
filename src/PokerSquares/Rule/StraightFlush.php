<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for straight flush rule
 */
class StraightFlush implements PokerRuleInterface
{
    use Nameable;
    use StraightTrait;
    use FlushTrait;

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("straight-flush");
    }



    /**
     * Check if rule is met
     * 
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool
    {
        return $this->isAStraight($cards) && $this->isAFlush($cards);
    }
}