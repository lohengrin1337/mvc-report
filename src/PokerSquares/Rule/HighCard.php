<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for high card rule
 */
class HighCard implements PokerRuleInterface
{
    use Nameable;
    use OfAKindTrait;

    /**
     * Constructor
     * Set name of rule
     * Set amount to 1 (of a kind)
     */
    public function __construct()
    {
        $this->setName("high-card");
        $this->amount = 1;
    }
}