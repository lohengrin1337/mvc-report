<?php

namespace App\Card;

use InvalidArgumentException as InvalidArgEx;

/**
 * Class for playing cards
 */
class Card implements CardInterface
{
    /**
     * @var string[] VALID_SUITS - the accepted categories
     */
    public const VALID_SUITS = [
        "hearts",
        "spades",
        "diamonds",
        "clubs",
    ];

    /**
     * @var int[] VALID_RANKS - the accepted values. (0 for joker)
     */
    public const VALID_RANKS = [
        1,
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
    ];

    /**
     * @var array<string|int,string> DEF_REPR - default representation
     */
    public const DEF_REPR = [
        "hearts" => "♥",
        "spades" => "♠",
        "diamonds" => "♦",
        "clubs" => "♣",
        11 => "J",
        12 => "K",
        13 => "Q",
        1 => "A"
    ];

    /**
     * @var string $suit - category (♥, ♠, ♦, ♣, plus joker)
     * @var int $rank - value (2 - 14, plus 0)
     */
    protected string $suit;
    protected int $rank;



    /**
     * Get card back
     *
     * @return string - Backside of a playing card
     */
    public static function getCardBack(): string
    {
        return "[*]";
    }



    /**
     * Validate suite and rank arguments
     *
     * @throws InvalidArgEx - if one not valid
     * @return bool - true if both valid
     */
    protected static function validateSuiteAndRank(string $suit, int $rank): bool
    {
        if (!in_array($suit, self::VALID_SUITS)) {
            throw new InvalidArgEx("Invalid suit provided!");
        }
        if (!in_array($rank, self::VALID_RANKS)) {
            throw new InvalidArgEx("Invalid rank provided!");
        }

        return true;
    }



    /**
     * Constructor
     * Assign valid suit and rank to card
     *
     * @param string $suit - category (♥, ♠, ♦, ♣, plus joker)
     * @param int $rank - value (2 - 14, plus 0)
     */
    public function __construct(string $suit, int $rank)
    {
        // $validSuits = $this->getValidSuits();
        // $validRanks = $this->getValidRanks();

        // if (!in_array($suit, $validSuits)) {
        //     throw new \InvalidArgumentException("Invalid suit provided!");
        // }
        // if (!in_array($rank, $validRanks)) {
        //     throw new \InvalidArgumentException("Invalid rank provided!");
        // }

        self::validateSuiteAndRank($suit, $rank);

        $this->suit = $suit;
        $this->rank = $rank;
    }


    // /**
    //  * Get array of valid suits
    //  *
    //  * @return array - the suits
    //  */
    // public static function getValidSuits(): array
    // {
    //     return Card::VALID_SUITS;
    // }



    // /**
    //  * Get array of valid ranks
    //  *
    //  * @return array - the ranks
    //  */
    // public static function getValidRanks(): array
    // {
    //     return Card::VALID_RANKS;
    // }



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

        $rankRepr = (string) $this->rank;
        if (array_key_exists($this->rank, self::DEF_REPR)) {
            $rankRepr = self::DEF_REPR[$this->rank];
        }

        return "[{$suitRepr}{$rankRepr}]";
    }
}

// "joker" => "🃟",
