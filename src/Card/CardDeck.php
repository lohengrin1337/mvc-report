<?php

namespace App\Card;

use InvalidArgumentException as InvalidArgEx;

/**
 * Class for deck of playing cards
 */
class CardDeck
{
    /**
     * @var CardInterface[] $cards - the Card classes
     */
    protected array $cards;

    /**
     * @var string cardBack - Backside of a playing card
     */
    private string $cardBack;



    /**
     * Get all valid suits
     *
     * @return string[] - the suits
     */
    public static function allSuits(): array
    {
        return [
            "hearts",
            "spades",
            "diamonds",
            "clubs",
        ];
    }



    /**
     * Get all valid ranks
     *
     * @return int[] - the ranks
     */
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
    public function __construct(string $cardClass)
    {
        if (!is_a($cardClass, CardInterface::class, true)) {
            throw new InvalidArgEx("$cardClass must implement CardInterface");
        }

        $this->cardBack = $cardClass::getCardBack();

        foreach (self::allSuits() as $suit) {
            foreach (self::allRanks() as $rank) {
                $this->add(new $cardClass($suit, $rank));
            }

            $this->shuffle();
        }
    }



    /**
     * Get card back
     *
     * @return string - The backside of a card
     */
    public function getCardBack(): string
    {
        return $this->cardBack;
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
     * Peak at the top card of the deck
     * 
     * @return CardInterface
     */
    public function peak(): CardInterface
    {
        if (!end($this->cards)) {
            return "";
        }
        return end($this->cards);
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
        usort($this->cards, function ($cardA, $cardB) {
            return $cardA->getRank() - $cardB->getRank();
        });

        // sort suits (♥, ♠, ♦, ♣)
        usort($this->cards, function ($cardA, $cardB) {
            $suitOrder = self::allSuits(); // array with the suits in right order
            $orderA = array_search($cardA->getSuit(), $suitOrder); // index 0-3
            $orderB = array_search($cardB->getSuit(), $suitOrder); // index 0-3

            // typecasting to make phpstan happy (array_search should not be returning false)
            return (int) $orderA - (int) $orderB;
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
     * @return string[] - array of strings
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
