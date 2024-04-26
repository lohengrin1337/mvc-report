<?php

namespace App\Card;

/**
 * Card interface
 */
interface CardInterface
{
    /**
     * @return string - the suit
     */
    public function getSuit(): string;



    /**
     * @return int - the rank
     */
    public function getRank(): int;


    /**
     * @param int $rank
     * @return bool
     */
    public function setRank(int $rank): bool;



    /**
     * @return string - representation
     */
    public function getAsString(): string;



    /**
     * @return string - representation of a card back
     */
    public static function getCardBack(): string;
}
