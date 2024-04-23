<?php

namespace App\Card;

// use InvalidArgumentException as InvalidArgEx;

/**
 * Class for card game 21
 */
class CardGame21
{
    /**
     * @var int MAX_SUM - The highest allowed sum for a hand
     */
    private const MAX_SUM = 21;



    /**
     * @var CardDeck $deck - a deck of cards
     * @var CardHand $player - a cardhand for player
     * @var CardHand $bank - a cardhand for bank
     */
    private CardDeck $deck;
    private CardHand $player;
    private CardHand $bank;



    /**
     * Check sum of hand is valid (<= MAX_SUM) - static
    */



    /**
     * Constructor
     * Add deck, player hand and bank hand
     */
    public function __construct(CardDeck $deck, CardHand $player, CardHand $bank)
    {
        $this->deck = $deck;
        $this->player = $player;
        $this->bank = $bank;
    }



    /**
     * Get current state of the game
     * @return array<string,mixed> - a representation of the current state
     */



    /**
     * Player draws a card
     */



    /**
     * Player stops, and saves the hand
     */




    /**
     * Bank plays
     */



    /**
     * Determine winner
     */



    /**
     * End game?
     */
}
