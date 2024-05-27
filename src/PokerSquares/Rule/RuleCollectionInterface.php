<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;

interface RuleCollectionInterface
{
    /**
     * @param CardInterface[] $hand
     * @return string - rule name of best matching rule
     */
    public function assessHand(array $hand): string;
}