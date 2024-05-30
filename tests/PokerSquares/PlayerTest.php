<?php

namespace App\PokerSquares;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    private Player $player;

    protected function setUp(): void
    {
        $this->player = new Player("UserName");
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Player::class, $this->player);
    }



    /**
     * Get name
     */
    public function testGetName(): void
    {
        $res = $this->player->getName();
        $this->assertEquals("UserName", $res);
    }
}

