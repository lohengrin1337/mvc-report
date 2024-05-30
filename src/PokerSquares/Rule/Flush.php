<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for flush rule
 */
class Flush implements PokerRuleInterface
{
    use Nameable;
    use FlushTrait;

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("flush");
    }



    /**
     * Check if rule is met
     * 
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool
    {
        return $this->isAFlush($cards);
    }
}