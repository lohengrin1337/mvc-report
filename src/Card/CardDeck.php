<?php

namespace App\Card;


/**
 * Class for deck of playing cards
 */
class CardDeck
{
    /**
     * @var array $cards - the Card classes
     */
    protected array $cards;



    // /**
    //  * Constructor
    //  * Add 52 uniqe cards to the deck
    //  */
    // public function __construct()
    // {
    //     $suits = Card::VALID_SUITS;
    //     $ranks = Card::VALID_RANKS;

    //     foreach ($suits as $suit) {
    //         foreach ($ranks as $rank) {
    //             $this->add(new Card($suit, $rank));
    //         }
    //     }
    // }



    public static function allSuits(): array
    {
        return [
            "hearts",
            "spades",
            "diamonds",
            "clubs",
        ];
    }



    public static function allRanks(): array
    {
        return array_merge(range(1, 13));
    }



    /**
     * Constructor
     * Add 52 uniqe cards to the deck
     * 
     * @param string $cardClass - a valid cardClass that implements CardInterface
     */
    public function __construct(string $cardClass = Card::class)
    {
        $suits = self::allSuits();
        $ranks = self::allRanks();

        // var_dump($suits);
        // var_dump($ranks);
        // var_dump((new $cardClass("hearts", 6))->getAsString());

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->add(new $cardClass($suit, $rank));
            }
        }
    }



    /**
     * Add a card to the deck
     * 
     * @param CardInterface $card - with CardInterface implementation
     */
    protected function add(CardInterface $card): void
    {
        $this->cards[] = $card;
    }



    /**
     * Draw a card from top of deck, if not empty
     *
     * @return ?CardInterface - a Card object or null
     */
    public function draw(): ?CardInterface
    {
        return array_pop($this->cards);
    }



    /**
     * Shuffle the deck of cards
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }



    /**
     * Sort the deck of cards
     */
    public function sort(): void
    {
        // sort by ranks ascending (2-14)
        usort($this->cards, function($a, $b) {
            return $a->getRank() - $b->getRank();
        });

        // sort suits (♥, ♠, ♦, ♣)
        usort($this->cards, function($a, $b) {
            $suitOrder = $a::VALID_SUITS; // array with the suits in right order
            $orderA = array_search($a->getSuit(), $suitOrder); // index 0-3
            $orderB = array_search($b->getSuit(), $suitOrder); // index 0-3
            return $orderA - $orderB;
        });
    }



    /**
     * Get count of remaining cards
     * 
     * @return int - the count
     */
    public function getCount(): int
    {
        return count($this->cards);
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
