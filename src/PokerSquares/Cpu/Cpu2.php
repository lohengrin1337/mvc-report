<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

class Cpu2 implements CpuLogicInterface
{
    /**
     * @var array<string,int> SUIT_TO_COL - maps every suit to a preferred column
     */
    private const SUIT_TO_COL = [
        "hearts" => 1,
        "spades" => 2,
        "diamonds" => 3,
        "clubs" => 4,
    ];

    /**
     * Suggest an empty slot on the bord for the current card
     * Cpu2 always tries to get flush in columns 1-4
     *
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface|null $card - the top card of the deck
     * @return int|null
     */
    public static function suggestPlacement(array $board, ?CardInterface $card): ?int
    {
        if (!$card) {
            return null;
        }

        // step 1 - find an empty slot in a matching column
        $slot = self::findPreferredSlot($board, $card);

        if (is_null($slot)) {
            // step 2 - find a slot in column 5 (trash column)
            $slot = self::findTrashSlot($board);
        }

        if (is_null($slot)) {
            // step 3 - find first available slot
            $slot = self::findFirstEmpty($board);
        }

        return $slot;
    }



    /**
     * Get the preferred column for a card
     *
     * @param CardInterface $card
     * @return int
     */
    protected static function getPreferredColumn(CardInterface $card): int
    {
        $suit = $card->getSuit();
        return self::SUIT_TO_COL[$suit];
    }


    /**
     * find an empty slot in the preferred column
     *
     * @param array<CardInterface|null> $board
     * @param CardInterface $card
     * @return int|null
     */
    protected static function findPreferredSlot(array $board, CardInterface $card): ?int
    {
        $preferredCol = self::getPreferredColumn($card);

        foreach ($board as $slot => $card) {
            if (
                str_ends_with($slot, (string) $preferredCol) &&
                is_null($card)
            ) {
                return $slot;
            }
        }
        return null;
    }



    /**
     * Find a slot in 'trash column' col 5
     *
     * @param array<CardInterface|null> $board
     * @return int|null
     */
    protected static function findTrashSlot(array $board): ?int
    {
        $col = "5";

        // find an empty slot in col5
        foreach ($board as $slot => $card) {
            if (
                str_ends_with($slot, $col) &&
                is_null($card)
            ) {
                return $slot;
            }
        }
        return null;
    }



    /**
     * Find any empty slot
     *
     * @param array<CardInterface|null> $board
     * @return int|null
     */
    protected static function findFirstEmpty(array $board): ?int
    {
        foreach ($board as $slot => $card) {
            if (is_null($card)) {
                return $slot;
            }
        }
        return null;
    }
}
