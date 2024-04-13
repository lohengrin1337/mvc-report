<?php

namespace App\Dice;

class Dice
{
    /**
     * @var int SIDES_DEFAULT - die with 6 sides as default
     * @var int SIDES_MIN - die with minimum 1 side
     */
    public const SIDES_DEFAULT = 6;
    public const SIDES_MIN = 1;



    /**
     * @var int $sides - number of sides
     * @var ?int $value - current die value
     */
    protected int $sides;
    protected ?int $value;



    /**
     * Constructor
     * 
     * @param int $sides number of sides
     */
    public function __construct(int $sides = self::SIDES_DEFAULT)
    {
        if ($sides < self::SIDES_MIN) {
            $sides = self::SIDES_MIN;
        }

        $this->sides = $sides;
        $this->value = null;
    }



    /**
     * Roll the die
     * 
     * @return int the value
     */
    public function roll(): int
    {
        $this->value = random_int(1, $this->sides);
        return $this->value;
    }



    /**
     * Get current die value
     * 
     * @return int the value
     */
    public function getValue(): int
    {
        return $this->value;
    }



    /**
     * Get current die value as string in square brackets "[$value]"
     * 
     * @return string the value as string
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }



    /**
     * Reset die - value = null
     */
    public function reset(): void
    {
        $this->value = null;
    }
}