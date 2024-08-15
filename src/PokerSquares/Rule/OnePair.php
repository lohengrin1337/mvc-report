<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for one pair rule
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
        $this->setName("one-pair");
        $this->amount = 2;
    }
}
