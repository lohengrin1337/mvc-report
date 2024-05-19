<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    private DiceGraphic $die;

    protected function setUp(): void
    {
        $this->die = new DiceGraphic(); // Die with no value
    }



    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDiceGraphic(): void
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf(DiceGraphic::class, $die);

        $res = $die->getValue();
        $this->assertNull($res);

        $res = $die->getAsString();
        $this->assertEmpty($res);
    }



    /**
     * Roll default die and check return value, plus value property
     */
    public function testRollDiceGraphic(): void
    {
        $res = $this->die->roll();
        $this->assertNotNull($res);

        $res = $this->die->getValue();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(6, $res);
    }



    /**
     * Roll die and verify value corresponds to string repr
     */
    public function testGetAsString(): void
    {
        $graphic = [
            '⚀',
            '⚁',
            '⚂',
            '⚃',
            '⚄',
            '⚅',
        ];

        $value = $this->die->roll();
        $this->assertEquals($graphic[$value - 1], $this->die->getAsString());
    }
}
