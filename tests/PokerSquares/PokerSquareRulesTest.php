<?php

namespace App\PokerSquares;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Flush.
 */
class PokerSquareRulesTest extends TestCase
{
    private RuleCollectionInterface $pokerSquareRules;

    /** @var mixed[] */
    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->pokerSquareRules = new PokerSquareRules();

        for ($i = 0; $i < 5; $i++) {
            $cardStub = $this->createStub(CardInterface::class);
            $this->cardStubs[] = $cardStub;
        }
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(PokerSquareRules::class, $this->pokerSquareRules);
    }



    /**
     * Assess a royalFlush hand
     */
    public function testAssessRoyalFlush(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(10);
        $this->cardStubs[1]->method("getRank")->willReturn(11);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(1);
        $this->cardStubs[0]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[1]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[2]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[3]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[4]->method("getSuit")->willReturn("hearts");

        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("royal-flush", $res);
    }



    /**
     * Assess a FourOFAKind hand
     */
    public function testAssessFourOFAKind(): void
    {
        for ($i = 0; $i < 4; $i++) {
            $this->cardStubs[$i]->method("getRank")->willReturn(1);
            $this->cardStubs[$i]->method("getSuit")->willReturn("hearts");
        }
        $this->cardStubs[4]->method("getRank")->willReturn(2);
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");


        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("four-of-a-kind", $res);
    }



    /**
     * Assess a Flush hand
     */
    public function testAssessFlush(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(10);
        $this->cardStubs[1]->method("getRank")->willReturn(6);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(1);
        $this->cardStubs[0]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[1]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[2]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[3]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[4]->method("getSuit")->willReturn("diamonds");

        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("flush", $res);
    }



    /**
     * Assess a ThreeOFAKind hand
     */
    public function testAssessThreeOFAKind(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->cardStubs[$i]->method("getRank")->willReturn(1);
            $this->cardStubs[$i]->method("getSuit")->willReturn("hearts");
        }
        $this->cardStubs[4]->method("getRank")->willReturn(2);
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");
        $this->cardStubs[4]->method("getRank")->willReturn(3);
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");


        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("three-of-a-kind", $res);
    }



    /**
     * Assess a OnePair hand
     */
    public function testAssessOnePair(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(1);
        $this->cardStubs[2]->method("getRank")->willReturn(5);
        $this->cardStubs[3]->method("getRank")->willReturn(11);
        $this->cardStubs[4]->method("getRank")->willReturn(9);
        $this->cardStubs[0]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[1]->method("getSuit")->willReturn("spades");
        $this->cardStubs[2]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[3]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[4]->method("getSuit")->willReturn("diamonds");


        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("one-pair", $res);
    }



    /**
     * Assess a HighCard hand
     */
    public function testAssessHighCard(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(3);
        $this->cardStubs[2]->method("getRank")->willReturn(5);
        $this->cardStubs[3]->method("getRank")->willReturn(11);
        $this->cardStubs[4]->method("getRank")->willReturn(9);
        $this->cardStubs[0]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[1]->method("getSuit")->willReturn("spades");
        $this->cardStubs[2]->method("getSuit")->willReturn("diamonds");
        $this->cardStubs[3]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[4]->method("getSuit")->willReturn("diamonds");

        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("high-card", $res);
    }



    /**
     * Assess an empty hand
     */
    public function testAssessEmptyHand(): void
    {
        $this->cardStubs = [
            null,
            null,
            null,
            null,
            null,
        ];

        $res = $this->pokerSquareRules->assessHand($this->cardStubs);
        $this->assertEquals("no-cards", $res);
    }
}
