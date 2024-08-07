<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use App\Entity\Board as BoardEntity;
use App\Exception\InvalidSlotException;

/**
 * Class for gameboard of poker squares game
 */
class Gameboard
{
    /**
     * @var array HANDS_TO_SLOTS - map hands to slots
     */
    private const HANDS_TO_SLOTS = [
        "row1" => ["11", "12", "13", "14", "15"],
        "row2" => ["21", "22", "23", "24", "25"],
        "row3" => ["31", "32", "33", "34", "35"],
        "row4" => ["41", "42", "43", "44", "45"],
        "row5" => ["51", "52", "53", "54", "55"],
        "col1" => ["11", "21", "31", "41", "51"],
        "col2" => ["12", "22", "32", "42", "52"],
        "col3" => ["13", "23", "33", "43", "53"],
        "col4" => ["14", "24", "34", "44", "54"],
        "col5" => ["15", "25", "35", "45", "55"],
    ];

    /**
     * @var array<Cardinterface|null> board - a key-value array with slots and cards
     */
    private array $board = [];



    /**
     * Constructor
     * Populate board with 25 empty slots (key "34" means row3 col4)
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
     * Get the gameboard
     * 
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }



    /**
     * Place a card on the board
     * 
     * @param string $slot - row and column as string ("11" means row 1 col 1)
     * @param CardInterface $card - a playing card
     * @throws InvalidSlotException - if slot is invalid
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
     * Get board with cards as strings (for visual representation)
     * 
     * @return array<string>
     */
    public function getBoardView(): array
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



    /**
     * Export board as Board entity
     * 
     * @return BoardEntity
     */
    public function exportAsEntity(): BoardEntity
    {
        $board = new BoardEntity();
        $board->setData($this->getBoardView());
        return $board;
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
     * Check if only 1 slot is filled
     * 
     * @return bool - true if only one card is placed on the board
     */
    public function boardHasOneCard(): bool
    {
        $nullCount = array_reduce($this->board, function($carry, $card) {
            return $carry + (is_null($card) ? 1 : 0);
        }, 0);

        return $nullCount === 24;
    }



    /**
     * Check if all 25 slots are filled
     * 
     * @return bool - true if board is full
     */
    public function boardIsFull(): bool
    {
        return !in_array(null, $this->board, true);
    }



    /**
     * Get all 10 poker hands (5 rows and 5 columns)
     * 
     * @return array<array<CardInterface|null>>
     */
    public function getAllHands(): array
    {
        // fill $hands with content of board slots (CardInterface|null)
        $hands = array_map(
            function($slots)
            {
                $hand = [];
                foreach ($slots as $slot) {
                    $hand[] = $this->board[$slot];
                }
                return $hand;
            },
            self::HANDS_TO_SLOTS
        );

        return $hands;
    }
}