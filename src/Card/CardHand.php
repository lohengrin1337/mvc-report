<?php

namespace App\Card;


/**
 * Class for hand of playing cards
 */
class CardHand
{
    /**
     * @var array $hand - an array with Card objects
     */
    private array $hand;



    /**
     * Add a card to hand
     * 
     * @param Card $card - a playing card
     */
    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }



    /**
     * Get all cards in hand as array of string representations
     * 
     * @return array - the strings in array
     */
    public function getAsString(): array
    {
        $stringRepresentation = [];
        foreach ($this->hand as $card) {
            $stringRepresentation[] = $card->getAsString();
        }

        return $stringRepresentation;
    }
}