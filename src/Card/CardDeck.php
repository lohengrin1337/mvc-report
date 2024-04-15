<?php

namespace App\Card;

use App\Card\Card;


/**
 * Class for deck of playing cards
 */
class CardDeck
{
    /**
     * @var array $cards - the Card classes
     */
    protected array $cards;



    /**
     * Constructor
     * Add 52 uniqe cards to the deck
     */
    public function __construct()
    {
        $suits = Card::VALID_SUITS;
        $ranks = Card::VALID_RANKS;

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->add(new Card($suit, $rank));
            }
        }
    }



    /**
     * Add a card to the deck
     * 
     * @param Card $card
     */
    private function add(Card $card): void
    {
        $this->cards[] = $card;
    }



    /**
     * Draw the top card of the deck
     *
     * @return Card
     */
    public function draw(): Card
    {
        return array_pop($this->cards);
    }



    
}
