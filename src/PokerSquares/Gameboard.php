<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\Exception\InvalidSlotException;

/**
 * Class for gameboard of poker squares game
 */
class Gameboard
{
    /**
     * @var array<Cardinterface|null> board - a key-value array with slots and cards
     */
    private array $board = [];



    /**
     * Constructor
     * Populate board with 25 empty slots
     */
    public function __construct()
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
            $this->board["$i$j"] = null;
            }
        }
    }



    /**
     * Validate slot exists, and value is null (no card yet)
     * 
     * @param string $slot
     * @return bool
     */
    private function slotIsValid(string $slot): bool
    {
        return array_key_exists($slot, $this->board) && is_null($this->board[$slot]);
    }



    /**
     * Place a card on the board
     * 
     * @param string $slot - row and column as string ("11" means row 1 col 1)
     * @param CardInterface $card - a playing card
     * @throws InvalidSlotException
     * @return void
     */
    public function placeCard(string $slot, CardInterface $card): void
    {
        if (!$this->slotIsValid($slot)) {
            throw new InvalidSlotException("'$slot' is not a valid slot on the gameboard!");
        }

        $this->board[$slot] = $card;
    }



    /**
     * Get board with cards as strings
     * 
     * @return array<string>
     */
    public function getAsString(): array
    {
        return array_map(
            function($card)
            {
                if (is_null($card)) {
                    return $card;
                }
                return $card->getAsString();
            },
            $this->board
        );
    }
}