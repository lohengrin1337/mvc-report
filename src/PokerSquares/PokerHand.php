<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use InvalidArgumentException;


/**
 * Class for poker hand (row or column) of poker squares game
 */
class PokerHand
{
    /**
     * @var Cardinterface[] $cards - a number of cards (usually 5)
     */
    private array $cards = [];

    /**
     * @var int $points - points of pokerhand (highest possible)
     */
    private int $points = 0;



    /**
     * Constructor
     * Populate hand with cards, and calculate points
     * 
     * @param Cardinterface[] $cards
     * @throws InvalidArgumentExcetion - if card doesnt implement CardInterface
     */
    public function __construct(array $cards)
    {
        foreach ($cards as $card) {
            if (!is_a($card, CardInterface::class)) {
                throw new InvalidArgumentException("Every card must implement CardInterface");
            }

            $this->cards[] = $card;
        }

        $this->calcPoints();
    }



    /**
     * Calculate highest possible points for pokerhand and set $this->points
     * 
     * @return void
     */
    private function calcPoints(): void
    {

    }



    /**
     * Get points of poker hand
     * 
     * @return int - points
     */
    public function getPoints(): int
    {
        return $this->points;
    }

}