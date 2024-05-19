<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    private DiceHand $diceHand;

    private Dice $diceStub;

    protected function setUp(): void
    {
        $this->diceHand = new DiceHand(); // Empty dicehand

        $this->diceStub = $this->createStub(Dice::class);
        $this->diceStub->method("getValue")->willReturn(5);
        $this->diceStub->/** @scrutinizer ignore-call */ 
                         method("getValue")->willReturn(5);
        $this->diceStub->/** @scrutinizer ignore-call */ 
                         method("getAsString")->willReturn("[5]");
    }



    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDiceHand(): void
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf(DiceHand::class, $diceHand);
    }



    /**
     * Avg of empty hand is 0, and string empty
     */
    public function testEmptyHand(): void
    {
        $res = $this->diceHand->getDiceCount();
        $this->assertEquals(0, $res);

        $res = $this->diceHand->getSum();
        $this->assertEquals(0, $res);

        $res = $this->diceHand->getAvg();
        $this->assertEquals(0, $res);

        $res = $this->diceHand->getValues();
        $this->assertEmpty($res);

        $res = $this->diceHand->getStringValues();
        $this->assertEmpty($res);
    }



    /**
     * Add a 2 diceStubs (val = 5), and verify methods
     */
    public function testAddDice(): void
    {
        $this->diceHand->add($this->diceStub);
        $this->diceHand->add($this->diceStub);

        $res = $this->diceHand->getDiceCount();
        $this->assertEquals(2, $res);

        $res = $this->diceHand->getSum();
        $this->assertEquals(10, $res);

        $res = $this->diceHand->getAvg();
        $this->assertEquals(5, $res);

        $res = $this->diceHand->getValues();
        $this->assertEquals([5, 5], $res);

        $res = $this->diceHand->getStringValues();
        $this->assertEquals(["[5]", "[5]"], $res);
    }



    /**
     * Add real Dice, roll and reset, sum is 0
     */
    public function testReset(): void
    {
        $this->diceHand->add(new Dice());
        $this->diceHand->add(new Dice());

        $this->diceHand->roll();
        $this->diceHand->reset();

        $res = $this->diceHand->getSum();
        $this->assertEquals(0, $res);
    }
}
