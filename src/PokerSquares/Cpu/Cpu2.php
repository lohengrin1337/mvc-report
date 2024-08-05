<?php

namespace App\PokerSquares\Cpu;

use App\Card\CardInterface;

class Cpu2 implements CpuLogicInterface
{
    /**
     * @var array SUIT_TO_COL - maps every suit to a preffered column
     */
    private const SUIT_TO_COL = [
        "hearts" => "1",
        "spades" => "2",
        "diamonds" => "3",
        "clubs" => "4",
    ];

    /**
     * @var array<CardInterface|null> $board - slots and cards
     */
    private array $board;

    /**
     * @var CardInterface|null $card - the top card of the deck
     */
    private array $card;

    /**
     * Suggest an empty slot on the bord for the current card
     * Cpu2 always tries to get flush in columns 1-4
     * 
     * @param array<CardInterface|null> $board - slots and cards
     * @param CardInterface $card - the top card of the deck
     * @return string|null
     */
    public function suggestPlacement(array $board, CardInterface $card): ?string
    {
        $this->$board = $board;
        $this->$card = $card;
        $slot = null;

        // step 1 - find an empty slot in a matching column
        $slot = $this->findPrefferedSlot();

        if (is_null($slot)) {
            // step 2 - find a slot in column 5 (trash column)
            $slot = $this->findTrashSlot();
        }

        if (is_null($slot)) {
            // step 3 - find first available slot
            $slot = $this->findFirstEmpty();
        }

        return $slot;

        // $suit = $card->getSuit();

        // $suitToCol = [
        //     "hearts" => "1",
        //     "spades" => "2",
        //     "diamonds" => "3",
        //     "clubs" => "4",
        // ];

        // $prefferedCol = $suitToCol[$suit];

        // // find an empty slot in the preffered column
        // foreach ($board as $slot => $card) {
        //     if (
        //         str_ends_with($slot, $prefferedCol) &&
        //         is_null($card)
        //     ){
        //         return $slot;
        //     }
        // }

        // // find an empty slot in col5
        // foreach ($board as $slot => $card) {
        //     if (
        //         str_ends_with($slot, "5") &&
        //         is_null($card)
        //     ){
        //         return $slot;
        //     }
        // }

        // // find first empty slot
        // foreach ($board as $slot => $card) {
        //     if (is_null($card)){
        //         return $slot;
        //     }
        // }
    }



    /**
     * Get the preffered column for a card
     * 
     * @return string|null
     */
    private function getPrefferedColumn(): ?string
    {
        if (!$this->card) {
            return null;
        }

        $suit = $this->card->getSuit();
        return self::SUIT_TO_COL[$suit];
    }


    /**
     * find an empty slot in the preffered column
     * 
     * @return string|null
     */
    private function findPrefferedSlot(): ?string
    {
        $prefferedCol = $this->getPrefferedColumn();

        foreach ($this->board as $slot => $card) {
            if (
                str_ends_with($slot, $prefferedCol) &&
                is_null($card)
            ){
                return $slot;
            }
        }
        return null;
    }



    /**
     * Find a slot in 'trash column' col 5
     * 
     * @return string|null
     */
    private function findTrashSlot(): ?string
    {
        // find an empty slot in col5
        foreach ($this->board as $slot => $card) {
            if (
                str_ends_with($slot, "5") &&
                is_null($card)
            ){
                return $slot;
            }
        }
        return null;
    }



    /**
     * Find any empty slot
     * 
     * @return string|null
     */
    private function findFirstEmpty(): ?string
    {
        foreach ($this->board as $slot => $card) {
            if (is_null($card)){
                return $slot;
            }
        }
        return null;
    }
}
