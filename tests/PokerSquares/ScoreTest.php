<?php

namespace App\PokerSquares;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Score.
 */
class ScoreTest extends TestCase
{
    private Score $score;

    protected function setUp(): void
    {
        $this->score = new Score();
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Score::class, $this->score);
    }



    /**
     * Set 10 points for all hands,
     * and get total (100), and single hand (10)
     */
    public function testTotalScore(): void
    {
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

        foreach ($hands as $handName) {
            $this->score->setHandScore($handName, 10);
        }

        $this->assertEquals(100, $this->score->getTotal());

        $res = $this->score->getDetails();
        $this->assertCount(10, $res);
        $this->assertEquals(10, $res["row5"]);
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
