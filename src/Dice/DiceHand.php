<?php

namespace App\Dice;

/**
 * Dicehand class
 */
class DiceHand
{
    /**
     * @var Dice[] $hand - a dice hand
     */
    private array $hand = [];



    /**
     * Add a die of class 'Dice' to hand
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }



    /**
     * Roll all dice
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
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
     * @return array<?int> - the values
     */
    public function getValues(): array
    {
        $values = [];

        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }

        return $values;
    }



    /**
     * Get the dice values as strings
     *
     * @return string[] - the string values
     */
    public function getStringValues(): array
    {
        $values = [];

        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
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

        return (int) array_sum($values);
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



    /**
     * Reset all dice - values = null
     */
    public function reset(): void
    {
        foreach ($this->hand as $die) {
            $die->reset();
        }
    }
}
