<?php

namespace App\Card;


/**
 * Class for playing cards
 */
class Card
{
    /**
     * @var array VALID_SUITS - the accepted categories
     */
    public const VALID_SUITS = [
        "hearts",
        "spades",
        "diamonds",
        "clubs",
        "joker",
    ];

    /**
     * @var array VALID_RANKS - the accepted values. (0 for joker)
     */
    public const VALID_RANKS = [
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        0,
    ];

    /**
     * @var array DEF_REPR - default representation
     */
    public const DEF_REPR = [
        "hearts" => "♥",
        "spades" => "♠",
        "diamonds" => "♦",
        "clubs" => "♣",
        "joker" => "🃟",
        11 => "J",
        12 => "K",
        13 => "Q",
        14 => "A"
    ];



    // /**
    //  * Validate suite and rank arguments
    //  * 
    //  * @return bool - true if valid, else false
    //  */
    // public static function validSuiteAndRank(string $suit, int $rank): bool
    // {
    //     if (!in_array($suit, self::VALID_SUITS)) {
    //         throw new \InvalidArgumentException("Invalid suit provided!");
    //     }
        
    //     if (!in_array($rank, self::VALID_RANKS)) {
    //         throw new \InvalidArgumentException("Invalid rank provided!");
    //     }
    // }



    /**
     * @var string $suit - category (♥, ♠, ♦, ♣, plus joker)
     * @var int $rank - value (2 - 14, plus 0)
     */
    protected string $suit;
    protected int $rank;



    /**
     * Constructor
     * Assign valid suit and rank to card
     * 
     * @var string $suit - category (♥, ♠, ♦, ♣, plus joker)
     * @var int $rank - value (2 - 14, plus 0)
     * 
     * @throws InvalidArgumentException - if invalid args
     */
    public function __construct(string $suit, int $rank)
    {
        if (!in_array($suit, self::VALID_SUITS)) {
            throw new \InvalidArgumentException("Invalid suit provided!");
        }
        if (!in_array($rank, self::VALID_RANKS)) {
            throw new \InvalidArgumentException("Invalid rank provided!");
        }

        $this->suit = $suit;
        $this->rank = $rank;
    }


    /**
     * Get suit (♥, ♠, ♦, ♣, or joker)
     * 
     * @return string - the suit
     */
    public function getSuit(): string
    {
        return $this->suit;
    }



    /**
     * Get rank (2 - 14, or 0)
     * 
     * @return int - the rank
     */
    public function getRank(): int
    {
        return $this->rank;
    }



    /**
     * Get card represented as string
     * 
     * @return string - representation [♥5]
     */
    public function getAsString(): string
    {
        $suitRepr = self::DEF_REPR[$this->suit];

        if (in_array($this->rank, self::DEF_REPR)) {
            $rankRepr = self::DEF_REPR[$this->rank];
        } elseif ($this->rank === 0) {
            $rankRepr = "";
        } else {
            $rankRepr = (string) $this->rank;
        }

        return "[{$suitRepr}{$rankRepr}]";
    }
}
