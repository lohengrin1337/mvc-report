<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\PokerSquares\Rule\PokerRuleInterface;
use App\PokerSquares\Rule\RoyalFlush;
use App\PokerSquares\Rule\StraightFlush;
use App\PokerSquares\Rule\FourOfAKind;
use App\PokerSquares\Rule\FullHouse;
use App\PokerSquares\Rule\Flush;
use App\PokerSquares\Rule\Straight;
use App\PokerSquares\Rule\ThreeOfAKind;
use App\PokerSquares\Rule\TwoPairs;
use App\PokerSquares\Rule\OnePair;
use App\PokerSquares\Rule\HighCard;

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
