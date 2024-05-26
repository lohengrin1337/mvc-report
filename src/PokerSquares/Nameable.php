<?php

namespace App\PokerSquares;

trait Nameable
{
    /**
     * @var string $name - name of rule
     */
    private string $name = "";



    /**
     * Get name of rule
     *
     * @return string - the name
     */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * Set name of rule
     *
     * @param string - a name
     * @return void
     */
    private function setName(string $name): void
    {
        $this->name = $name;
    }
}