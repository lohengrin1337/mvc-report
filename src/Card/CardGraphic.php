<?php

namespace App\Card;

/**
 * Class for playing cards with utf-8 representation
 */
class CardGraphic extends Card
{
    /**
     * @var string UTF8_START - start of UTF8 string, same for all cards
     * @var array UTF8_MID - middle of UTF8 string, depending on card suit
     * @var array UTF8_END - end of UTF8 string, depending on card rank
     */
    public const UTF8_START = "&#x1f0";
    public const UTF8_MID = [
        "hearts" => "b",
        "spades" => "a",
        "diamonds" => "c",
        "clubs" => "d",
    ];
    public const UTF8_END = [
        10 => "a",
        11 => "b",
        12 => "d",
        13 => "e"
    ];



    /**
     * Get card represented as UTF8 card
     *
     * @return string - representation (&#x1f0de)
     */
    public function getAsString(): string
    {
        $utf8Start = self::UTF8_START;

        $utf8Mid = self::UTF8_MID[$this->suit];

        if (array_key_exists($this->rank, self::UTF8_END)) {
            $utf8End = self::UTF8_END[$this->rank];
        } elseif ($this->rank === 0) {
            $utf8End = "";
        } else {
            $utf8End = (string) $this->rank;
        }

        return $utf8Start . $utf8Mid . $utf8End;
    }
}
