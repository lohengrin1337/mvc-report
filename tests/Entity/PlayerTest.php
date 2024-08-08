<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;


/**
 * Test cases for Player Entity.
 */
class PlayerTest extends TestCase
{
    private Player $player;

    protected function setUp(): void
    {
        $player = new Player();
        $player->setName("UserName");
        $round = $this->createStub(Round::class);
        $player->addRound($round);
        $this->player = $player;
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Player::class, $this->player);
    }



    /**
     * Get id
     */
    public function testGetId(): void
    {
        $this->assertNull($this->player->getId());
    }



    /**
     * Get name
     */
    public function testGetName(): void
    {
        $this->assertEquals("UserName", $this->player->getName());
    }



    /**
     * set and get type
     */
    public function testSetAndGetType(): void
    {
        $this->player->setType("cpu");
        $this->assertEquals("cpu", $this->player->getType());
    }



    /**
     * set and get level
     */
    public function testSetAndGetLevel(): void
    {
        $this->player->setLevel(2);
        $this->assertEquals(2, $this->player->getLevel());
    }



    /**
     * set and get rounds
     */
    public function testSetAndGetRounds(): void
    {
        $this->assertInstanceOf(Round::class, $this->player->getRounds()[0]);
    }



    /**
     * remove round and non existing round
     */
    public function testRemoveRound(): void
    {
        $round = $this->player->getRounds()[0];
        $round->method("getPlayer")->willReturn($this->player);
        $this->player->removeRound($round);
        $this->assertEmpty($this->player->getRounds());

        $newRound = $this->createStub(Round::class);
        $this->player->removeRound($newRound);
    }
}
