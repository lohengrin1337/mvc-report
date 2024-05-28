<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for four of a kind rule
 */
class FourOfAKind implements PokerRuleInterface
{
    use Nameable;
    use OfAKindTrait;

    /**
     * Constructor
     * Set name of rule
     * Set amount to 4 (of a kind)
     */
    public function __construct()
    {
        $this->setName("four-of-a-kind");
        $this->amount = 4;
    }
}