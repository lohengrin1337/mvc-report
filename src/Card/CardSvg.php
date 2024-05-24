<?php

namespace App\Card;

/**
 * Class for playing cards with svg representation
 */
class CardSvg extends Card
{
    /**
     * @var string CLASS_PREFIX - start of class name
     */
    public const CLASS_PREFIX = "svg-card-";

    /**
     * @var array<string> CLASS_MID - mid of class name
     */
    public const CLASS_MID = [
        1 => "a",
        11 => "j",
        12 => "q",
        13 => "k",
        14 => "a",
    ];

    /**
     * @var array<string> CLASS_SUFFIX - end of class name
     */
    public const CLASS_SUFFIX = [
        "hearts" => "h",
        "spades" => "s",
        "diamonds" => "d",
        "clubs" => "c",
    ];



    /**
     * Get card back
     *
     * @return string - Backside of a playing card (class name for svg)
     */
    public static function getCardBack(): string
    {
        return self::CLASS_PREFIX . "back";
    }



    /**
     * Get card represented as SVG
     *
     * @return string - class name (svg-card-as)
     */
    public function getAsString(): string
    {
        // convert rank to a part of the class name
        $classMid = (string) $this->rank;
        if (array_key_exists($this->rank, self::CLASS_MID)) {
            $classMid = self::CLASS_MID[$this->rank];
        }

        // convert suit to a part of the class name
        $classSuffix = self::CLASS_SUFFIX[$this->suit];

        return self::CLASS_PREFIX . $classMid . $classSuffix;
    }
}
