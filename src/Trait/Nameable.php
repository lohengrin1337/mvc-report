<?php

namespace App\Trait;

trait Nameable
{
    /**
     * @var string $name
     */
    private string $name = "";



    /**
     * Get name
     *
     * @return string - the name
     */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * Set name
     *
     * @param string - a name
     * @return void
     */
    private function setName(string $name): void
    {
        $this->name = $name;
    }
}
