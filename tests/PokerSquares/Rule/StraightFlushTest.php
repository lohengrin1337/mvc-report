<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class StraightFlush.
 */
class StraightFlushTest extends TestCase
{
    private StraightFlush $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new StraightFlush();

        for ($i=0; $i < 5; $i++) { 
            $cardStub = $this->createStub(CardInterface::class);
            $this->cardStubs[] = $cardStub;
        }
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(StraightFlush::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid StraightFlush (1,2,3,4,5 of hearts)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(3);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(5);
        $this->cardStubs[0]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[1]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[2]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[3]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[4]->method("getSuit")->willReturn("hearts");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid StraightFlush (1,2,3,4,6 of hearts)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(3);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(6);
        $this->cardStubs[0]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[1]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[2]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[3]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[4]->method("getSuit")->willReturn("hearts");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }



        /**
     * Check a hand of card stubs is also NOT a valid StraightFlush (1,2,3,4,5 of hearts and diamonds)
     */
    public function testCheckHandInvalid2(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(3);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(5);
        $this->cardStubs[0]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[1]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[2]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[3]->method("getSuit")->willReturn("hearts");
        $this->cardStubs[4]->method("getSuit")->willReturn("diamonds");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
