<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\PokerSquares\Rule\PokerRuleInterface;
use App\PokerSquares\Rule;

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
        // add rules in order from highest to lowest
        $this->rules = [
            new Rule\RoyalFlush(),
            new Rule\StraightFlush(),
            new Rule\FourOfAKind(),
            new Rule\FullHouse(),
            new Rule\Flush(),
            new Rule\Straight(),
            new Rule\ThreeOfAKind(),
            new Rule\TwoPairs(),
            new Rule\OnePair(),
            new Rule\HighCard(),
        ];
    }


    /**
     * Use rules to assess highest met rule, or none
     *
     * @param array<CardInterface|null> $hand
     * @return string - rule name of best matching rule
     */
    public function assessHand(array $hand): string
    {
        // get the actual cards from hand (no null values)
        $cards = array_filter($hand);

        $ruleName = "no-cards";
        if(!$cards) {
            return $ruleName;  // occurs if hand is empty
        }

        foreach ($this->rules as $rule) {
            if ($rule->checkHand($cards)) {
                $ruleName = $rule->getName();
                return $ruleName;
            }
        }

        return $ruleName; // should never happen
    }
}
