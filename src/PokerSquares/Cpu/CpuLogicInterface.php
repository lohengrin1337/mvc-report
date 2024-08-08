<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

interface CpuLogicInterface {
    /**
     * Suggest an empty slot on the bord for the current card
     * 
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface $card - the top card of the deck
     * @return int|null
     */
    public static function suggestPlacement(array $board, CardInterface $card): ?int;
}
