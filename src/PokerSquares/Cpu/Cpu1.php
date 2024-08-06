<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

class Cpu1 implements CpuLogicInterface
{
    /**
     * Suggest an empty slot on the bord for the current card
     * Cpu1 simply suggests a random empty slot
     * 
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface $card - the top card of the deck
     * @return string|null
     */
    public static function suggestPlacement(array $board, CardInterface $card): ?string
    {
        $emptySlots = [];
        foreach ($board as $slot => $card) {
            if (is_null($card)) {
                $emptySlots[] = $slot;
            }
        }

        if (!$emptySlots) {
            return null;
        }

        $randomIndex = rand(0, count($emptySlots) - 1);

        return $emptySlots[$randomIndex];
    }
}
