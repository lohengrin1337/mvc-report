<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for three of a kind rule
 */
class ThreeOfAKind implements PokerRuleInterface
{
    use Nameable;
    use OfAKindTrait;

    /**
     * Constructor
     * Set name of rule
     * Set amount to 3 (of a kind)
     */
    public function __construct()
    {
        $this->setName("three-of-a-kind");
        $this->amount = 3;
    }
}
