<?php

namespace Oljn\Card;

/**
 * Card interface
 */
interface CardInterface
{
    /**
     * @param string $suit
     * @param int $rank
     */
    public function __construct(string $suit, int $rank);



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
