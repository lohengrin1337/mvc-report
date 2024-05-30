<?php

namespace App\PokerSquares;

use App\Card\CardInterface;

interface RuleCollectionInterface
{
    /**
     * @param array<CardInterface|null> $hand
     * @return string - rule name of best matching rule
     */
    public function assessHand(array $hand): string;
}