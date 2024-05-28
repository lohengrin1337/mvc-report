<?php

namespace App\PokerSquares\Rule;

use App\Trait\Nameable;

/**
 * Class for straight rule
 */
class Straight implements PokerRuleInterface
{
    use Nameable;
    use StraightTrait;

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("straight");
    }



    /**
     * Check if rule is met
     * 
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool
    {
        return $this->isAStraight($cards);
    }
}