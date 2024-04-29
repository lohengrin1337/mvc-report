<?php

namespace Oljn\Dice;

/**
 * DiceGraphicts class
 * Dice with graphic representation
 */
class DiceGraphic extends Dice
{
    /**
     * @var string[] GRAPHIC - a graphic representation of a 6 sided die
     */
    private const GRAPHIC = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];



    /**
     * Constructor
     * Assuring the die has 6 sides
     */
    public function __construct()
    {
        parent::__construct(self::SIDES_DEFAULT);
    }



    /**
     * Get a graphic representation of current value
     *
     * @return string the graphic
     */
    public function getAsString(): string
    {
        if (!$this->value) {
            return "";
        }

        return self::GRAPHIC[$this->value - 1];
    }
}
