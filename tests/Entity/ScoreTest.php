<?php

namespace App\Entity;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for Score Entity.
 */
class ScoreTest extends TestCase
{
    private Score $score;

    protected function setUp(): void
    {
        $score = new Score();

        $hands = [
            "row1",
            "row2",
            "row3",
            "row4",
            "row5",
            "col1",
            "col2",
            "col3",
            "col4",
            "col5",
        ];

        // set 10 as score of every hand
        foreach ($hands as $handName) {
            $score->setHandScore($handName, 10);
        }

        $this->score = $score;
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Score::class, $this->score);
    }



    /**
     * Get id
     */
    public function testGetId(): void
    {
        $this->assertNull($this->score->getId());
    }



    /**
     * get total score = 100
     */
    public function testTotalScore(): void
    {
        $this->assertEquals(100, $this->score->getTotal());
    }



    /**
     * get hand score = 10
     */
    public function testHandScore(): void
    {
        $this->assertEquals(10, $this->score->getHands()["row5"]);
    }



    /**
     * Invalid hand name - expectException
     */
    public function testInvalidHandName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->score->setHandScore("invalid", 10);
    }
}
