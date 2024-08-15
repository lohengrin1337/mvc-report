<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Straight.
 */
class StraightTest extends TestCase
{
    private Straight $rule;

    /** @var mixed[] */
    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new Straight();

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
        $this->assertInstanceOf(Straight::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid Straight (1,2,3,4,5)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(3);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(5);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is also a valid Straight (12,2,13,3,1)
     */
    public function testCheckHandValid2(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(12);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(13);
        $this->cardStubs[3]->method("getRank")->willReturn(3);
        $this->cardStubs[4]->method("getRank")->willReturn(1);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid Straight (1,2,3,4,6)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(1);
        $this->cardStubs[1]->method("getRank")->willReturn(2);
        $this->cardStubs[2]->method("getRank")->willReturn(3);
        $this->cardStubs[3]->method("getRank")->willReturn(4);
        $this->cardStubs[4]->method("getRank")->willReturn(6);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
