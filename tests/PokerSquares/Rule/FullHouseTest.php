<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class FullHouse.
 */
class FullHouseTest extends TestCase
{
    private FullHouse $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new FullHouse();

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
        $this->assertInstanceOf(FullHouse::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid FullHouse (12,12,12,13,13)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(12);
        $this->cardStubs[1]->method("getRank")->willReturn(12);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(13);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid FullHouse (12,12,13,13,14)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(12);
        $this->cardStubs[1]->method("getRank")->willReturn(12);
        $this->cardStubs[2]->method("getRank")->willReturn(13);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(14);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
