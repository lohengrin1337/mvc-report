<?php

namespace App\Dice;

class Dice
{
    /**
     * @var int SIDES_DEFAULT die with 6 sides as default
     */
    public const SIDES_DEFAULT = 6;



    /**
     * @var ?int $value current die value
     * @var ?int $sides number of sides
     */
    protected ?int $value;
    protected ?int $sides;



    /**
     * Constructor
     * 
     * @param int $sides number of sides
     */
    public function __construct(int $sides = self::SIDES_DEFAULT)
    {
        $this->value = null;
        $this->sides = $sides;
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
}