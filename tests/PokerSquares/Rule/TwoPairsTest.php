<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class TwoPairs.
 */
class TwoPairsTest extends TestCase
{
    private TwoPairs $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new TwoPairs();

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
        $this->assertInstanceOf(TwoPairs::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid TwoPairs (5,5,6,6,7)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(5);
        $this->cardStubs[1]->method("getRank")->willReturn(5);
        $this->cardStubs[2]->method("getRank")->willReturn(6);
        $this->cardStubs[3]->method("getRank")->willReturn(6);
        $this->cardStubs[4]->method("getRank")->willReturn(7);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid TwoPairs (5,5,7,8,9)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(5);
        $this->cardStubs[1]->method("getRank")->willReturn(5);
        $this->cardStubs[2]->method("getRank")->willReturn(7);
        $this->cardStubs[3]->method("getRank")->willReturn(8);
        $this->cardStubs[4]->method("getRank")->willReturn(9);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
