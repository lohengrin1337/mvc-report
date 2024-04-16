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
     * Draw top cards of the deck
     *
     * @param int $num - number of cards to draw
     * @return array - array of Card objects
     */
    public function draw($num = 1): array
    {
        $cardDraw = [];
        for ($i = 0; $i < $num; $i++) {
            $cardDraw[] = array_pop($this->cards);
        }

        return $cardDraw;
    }



    /**
     * Shuffle the deck of cards
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }



    /**
     * Get representation of all cards
     * 
     * @return array - array of strings
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
