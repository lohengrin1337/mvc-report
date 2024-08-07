<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

class Cpu3 extends Cpu2 implements CpuLogicInterface
{
    /**
     * Suggest an empty slot on the bord for the current card
     * Cpu3 always tries to get flush in columns 1-4 like Cpu2,
     * and also put the card in a row with a card of same rank
     * 
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface $card - the top card of the deck
     * @return string|null
     */
    public static function suggestPlacement(array $board, CardInterface $card): ?string
    {
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
     * find an empty slot in the preferred column and row
     * 
     * @param array $board
     * @param CardInterface $card
     * @return string|null
     */
    private static function findPreferredSlot(array $board, CardInterface $card): ?string
    {
        $rows = self::getPreferredRows($board, $card);
        $col = self::getPreferredColumn($card);

        foreach ($rows as $row) {
            $slot = $row . $col;
            if (!$board[$slot]) {
                return $slot;
            }
        }

        // find any empty slot in preferred column
        return parent::findPreferredSlot($board, $card);
    }



    /**
     * Find a slot in 'trash column' col 5, try to find row with matching rank
     * 
     * @param array $board
     * @param CardInterface $card
     * @return string|null
     */
    private static function findTrashSlot(array $board, CardInterface $card): ?string
    {
        $rows = self::getPreferredRows($board, $card);
        $col = "5";

        foreach ($rows as $row) {
            $slot = $row . $col;
            if (!$board[$slot]) {
                return $slot;
            }
        }

        // find any empty slot in trash column
        return parent::findTrashSlot($board);
    }



    /**
     * Find any empty slot in preferred row
     * 
     * @param array $board
     * @param CardInterface $card
     * @return string|null
     */
    private static function findFirstEmpty(array $board, CardInterface $card): ?string
    {
        $rows = self::getPreferredRows($board, $card);

        foreach ($rows as $row) {
            foreach ($board as $slot => $boardCard) {
                if (
                    $slot[0] === $row &&
                    is_null($boardCard)
                ){
                    return $slot;
                }
            }
        }

        return parent::findFirstEmpty();
    }



    /**
     * Get the preferred rows for a card (rows with most cards of same rank first)
     * 
     * @param array $board
     * @param CardInterface $card
     * @return array
     */
    private static function getPreferredRows(array $board, CardInterface $card): array
    {
        $rank = $card->getRank();

        // find all rows with cards of same rank
        $rows = array_filter($board, function($boardCard) use ($rank){
            return $boardCard && $boardCard->getRank() === $rank;
        });

        // count cards of each row
        $rowCount = array_count_values($rows);

        // sort on count decending
        arsort($rowCount);

        // return the rows
        return array_keys($rowCount);
    }
}
