<?php

namespace App\Card;


/**
 * Class for hand of playing cards
 */
class CardHand
{
    /**
     * @var array $cards - an array with Card objects
     */
    private array $cards = [];




    /**
     * Draw a card from deck, and add to hand
     * 
     * @param CardDeck $deck - a deck of playing card
     * @param int $num - number of cards to draw
     */
    public function draw(CardDeck $deck, int $num = 1): void
    {
        for ($i = 0; $i < $num; $i++) {
            $card = $deck->draw();
            if ($card) {
                $this->cards[] = $card;
            }
        }
    }



    /**
     * Get the amount of cards in hand
     * 
     * @return int - the count
     */
    public function cardCount(): int
    {
        return count($this->cards);
    }



    /**
     * Get all cards in hand as array of string representations
     * 
     * @return array - the strings in array
     */
    public function getAsString(): array
    {
        $stringRepresentation = [];
        foreach ($this->cards as $card) {
            $stringRepresentation[] = $card->getAsString();
        }

        return $stringRepresentation;
    }
}