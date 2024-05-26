<?php

namespace App\PokerSquares;

/**
 * Class for one pair rule - poker hand
 */
class OnePair implements PokerRuleInterface
{
    use Nameable;
    use OfAKindTrait;

    /**
     * Constructor
     * Set name of rule
     * Set amount to 2 (of a kind)
     */
    public function __construct()
    {
        $this->setName("one pair");
        $this->amount = 2;
    }
}