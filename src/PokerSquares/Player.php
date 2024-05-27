<?php

namespace App\PokerSquares;

use App\Trait\Nameable;


class Player
{
    use Nameable;

    /**
     * Constructor
     * Set name
     * 
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

}