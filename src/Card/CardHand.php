<?php

namespace App\Card;

/**
 * Class for hand of playing cards
 */
class CardHand
{
    /**
     * @var CardInterface[] $cards - an array with Card objects
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
     * Get the last card from hand
     *
     * @return CardInterface|null
     */
    public function getLastCard(): ?CardInterface
    {
        return end($this->cards) ?: null;
    }



    /**
     * Set rank of last card
     *
     * @param int $rank
     * @return bool - true if successful, else false
     */
    public function setLastCardRank(int $rank): bool
    {
        $lastCard = end($this->cards);
        if (!$lastCard) {
            return false;
        }

        return $lastCard->setRank($rank);
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
     * Get sum of ranks for cards
     *
     * @return int - the sum
     */
    public function rankSum(): int
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            $sum += $card->getRank();
        }

        return $sum;
    }



    /**
     * Get all cards in hand as array of string representations
     *
     * @return string[] - the strings in array
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
