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
     * @return string - representation
     */
    public function getAsString(): string;
}