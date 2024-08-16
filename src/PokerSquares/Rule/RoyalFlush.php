<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use App\Trait\Nameable;

/**
 * Class for royal flush rule
 */
class RoyalFlush implements PokerRuleInterface
{
    use Nameable;
    use StraightTrait;
    use FlushTrait;

    /**
     * @var array<array<int>> ROYAL_STRAIGHTS
     */
    private const ROYAL_STRAIGHTS = [[10,11,12,13,1]];

    /**
     * Constructor
     * Set name of rule
     */
    public function __construct()
    {
        $this->setName("royal-flush");
    }



    /**
     * Check if rule is met
     *
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool
    {
        return $this->isAStraight($cards, self::ROYAL_STRAIGHTS) && $this->isAFlush($cards);
    }
}
