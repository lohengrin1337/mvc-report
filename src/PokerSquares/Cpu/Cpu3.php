<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

class Cpu3 extends Cpu2 implements CpuLogicInterface
{
    /**
     * Suggest an empty slot on the bord for the current card
     * Cpu3 always tries to get flush in columns 1-4 like Cpu2,
     * and also put the card in a row with most cards of same rank
     * 
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface $card - the top card of the deck
     * @return int|null
     */
    public static function suggestPlacement(array $board, CardInterface $card): ?int
    {
        // step 1 - find an empty slot in a matching column and preferred row
        $slot = self::findPreferredSlot($board, $card);

        if (is_null($slot)) {
            // step 2 - find a slot in column 5 and preferred row
            $slot = self::findPreferredTrashSlot($board, $card);
        }

        if (is_null($slot)) {
            // step 3 - find first available slot in preferred row
            $slot = self::findPreferredFirstEmpty($board, $card);
        }

        return $slot;
    }



    /**
     * find an empty slot in a preferred column and row
     * 
     * @param array<CardInterface|null> $board
     * @param CardInterface $card
     * @return int|null
     */
    protected static function findPreferredSlot(array $board, CardInterface $card): ?int
    {
        $rows = self::getPreferredRows($board, $card);
        $col = self::getPreferredColumn($card);

        foreach ($rows as $row) {
            $slot = $row . $col;
            if (!$board[$slot]) {
                return (int) $slot;
            }
        }

        // find any empty slot in preferred column
        return parent::findPreferredSlot($board, $card);
    }



    /**
     * Find a slot in 'trash column' col 5, first try to find row with matching rank
     * 
     * @param array<CardInterface|null> $board
     * @param CardInterface $card
     * @return int|null
     */
    protected static function findPreferredTrashSlot(array $board, CardInterface $card): ?int
    {
        $rows = self::getPreferredRows($board, $card);
        $col = 5; // trash column

        foreach ($rows as $row) {
            $slot = $row . $col;
            if (!$board[$slot]) {
                return (int) $slot;
            }
        }

        // find any empty slot in trash column
        return parent::findTrashSlot($board);
    }



    /**
     * Find any empty slot in preferred row
     * 
     * @param array<CardInterface|null> $board
     * @param CardInterface $card
     * @return int|null
     */
    protected static function findPreferredFirstEmpty(array $board, CardInterface $card): ?int
    {
        $preferredRows = self::getPreferredRows($board, $card);

        // filter out the relevant slots (empty and in a preferred row)
        $relevantSlots = array_filter($board, function($boardCard, $slot) use ($preferredRows) {
            $row = substr($slot, 0, 1);
            return !$boardCard && in_array($row, $preferredRows);
        }, ARRAY_FILTER_USE_BOTH);

        // return best matching slot
        foreach ($preferredRows as $preferredRow) {
            foreach (array_keys($relevantSlots) as $slot) {
                $row = substr($slot, 0, 1);
                if ($row === $preferredRow) {
                    return $slot;
                }
            }
        }

        // find any empty slot
        return parent::findFirstEmpty($board);
    }



    /**
     * Get the preferred rows for a card (rows with most cards of same rank first)
     * 
     * @param array<CardInterface|null> $board
     * @param CardInterface $card
     * @return array<int>
     */
    protected static function getPreferredRows(array $board, CardInterface $card): array
    {
        $rank = $card->getRank();

        // find all rows with cards of same rank
        $relevantCards = array_filter($board, function($boardCard) use ($rank){
            return $boardCard && $boardCard->getRank() === $rank;
        });

        // get the rows of relevant cards (one for each card)
        $rows = array_map(function($slot) {
             return substr($slot, 0, 1);
        }, array_keys($relevantCards));

        // count amount of each row
        $rowCount = array_count_values($rows);

        // sort on count decending
        arsort($rowCount);

        return array_keys($rowCount);
    }
}
