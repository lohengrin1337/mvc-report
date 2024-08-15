<?php

namespace App\PokerSquares;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class AmericanScores.
 */
class AmericanScoresTest extends TestCase
{
    private ScoreMappingInterface $scoreMap;

    protected function setUp(): void
    {
        $this->scoreMap = new AmericanScores();
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(AmericanScores::class, $this->scoreMap);
    }



    /**
     * Get sum of points of all rules (302)
     */
    public function testAllScores(): void
    {
        $rules = [
            "royal-flush",
            "straight-flush",
            "four-of-a-kind",
            "full-house",
            "flush",
            "straight",
            "three-of-a-kind",
            "two-pairs",
            "one-pair",
            "high-card",
        ];

        $sumScores = 0;
        foreach ($rules as $ruleName) {
            $sumScores += $this->scoreMap->getScore($ruleName);
        }

        $this->assertEquals(302, $sumScores);
    }



    /**
     * Invalid rule key - expectException
     */
    public function testInvalidRuleArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->scoreMap->getScore("invalid-rule-name");
    }
}
