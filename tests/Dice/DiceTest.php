<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    private Dice $die;

    protected function setUp(): void
    {
        $this->die = new Dice(); // Die with no value
    }



    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getValue();
        $this->assertNull($res);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }



    /**
     * Roll default die and check return value, plus value property
     */
    public function testRollDice(): void
    {
        $res = $this->die->roll();
        $this->assertNotNull($res);

        $res = $this->die->getValue();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(6, $res);
    }



    /**
     * Roll die with 4 sides and check return value, plus value property
     */
    public function testRoll4SidedDice(): void
    {
        $die = new Dice(4);

        $res = $die->roll();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(4, $res);

        $res = $die->getValue();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(4, $res);
    }



    /**
     * Try to construct -5 sided die, and confirm 1 side is used
     */
    public function testRoll1SidedDice(): void
    {
        $die = new Dice(-5);

        $res = $die->roll();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(1, $res);
    }



    /**
     * Roll die and verify value corresponds to string repr
     */
    public function testGetAsString(): void
    {
        $value = $this->die->roll();
        $this->assertEquals($this->die->getAsString(), "[{$value}]");
    }



    /**
     * Roll and reset - confirm value is null
     */
    public function testResetDice(): void
    {
        $this->die->roll();
        $this->die->reset();

        $this->assertNull($this->die->getValue());
    }
}
