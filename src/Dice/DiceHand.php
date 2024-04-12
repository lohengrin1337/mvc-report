<?php

namespace App\Dice;



/**
 * Dicehand class
 */
class DiceHand
{
    /**
     * @var array $hand - a dice hand
     */
    private array $hand = [];



    /**
     * Add a dice of class 'Dice' to hand
     */
    public function add(Dice $dice): void
    {
        $this->hand[] = $dice;
    }



    /**
     * Roll all dice
     */
    public function roll(): void
    {
        foreach ($this->hand as $dice) {
            $dice->roll();
        }
    }



    /**
     * Get number of dice
     * 
     * @return int - the count
     */
    public function getDiceCount(): int
    {
        return count($this->hand);
    }



    /**
     * Get the dice values
     * 
     * @return array - the values
     */
    public function getValues(): array
    {
        $values = [];

        foreach ($this->hand as $dice) {
            $values[] = $dice->getValue();
        }

        return $values;
    }



    /**
     * Get the dice values as strings
     * 
     * @return array - the string values
     */
    public function getStringValues(): array
    {
        $values = [];

        foreach ($this->hand as $dice) {
            $values[] = $dice->getAsString();
        }

        return $values;
    }



    /**
     * Get the sum of current dice values
     * 
     * @return int - the sum
     */
    public function getSum(): int
    {
        $values = $this->getValues();

        return array_sum($values);
    }



    /**
     * Get the average of current dice values
     * 
     * @return float - the average
     */
    public function getAvg(): float
    {
        $sum = $this->getSum();

        if (!$sum) {
            return 0;
        }

        $count = $this->getDiceCount();

        $avg = $sum / $count;

        return round($avg, 1);
    }
}