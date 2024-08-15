<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;

/**
 * Interface for poker hand rules
 */
interface PokerRuleInterface
{
    /**
     * Check if a hand meets the rules of a poker hand
     *
     * @param CardInterface[] $cards - array of cards to check
     * @return bool - true if rule is met
     */
    public function checkHand(array $cards): bool;



    /**
     * Get name of rule
     *
     * @return string - name of rule
     */
    public function getName(): string;
}
