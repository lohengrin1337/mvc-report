<?php

namespace App\PokerSquares\Rule;

/**
 * Class holding a set of Poker Square rules
 */
class PokerSquareRules implements RuleCollectionInterface
{
    /**
     * @var PokerRuleInterface[] $rules - a set of rules
     */
    private array $rules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = [
            new RoyalFlush(),
            new StraightFlush(),
            new FourOfAKind(),
            new FullHouse(),
            new Flush(),
            new Straight(),
            new ThreeOfAKind(),
            new TwoPairs(),
            new OnePair(),
            new HighCard(),
        ];
    }


    /**
     * Use rules to assess highest met rule, or none
     *
     * @param CardInterface[] $hand
     * @return string - rule name of best matching rule
     */
    public function assessHand(array $hand): string
    {
        foreach ($this->rules as $rule) {
            if ($rule->checkHand($hand)) {
                return $rule->getName();
            }
        }

        return "";  // occurs if hand is empty
    }
}