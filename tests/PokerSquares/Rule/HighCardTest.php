<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class HighCard.
 */
class HighCardTest extends TestCase
{
    private HighCard $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new HighCard();

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
        $this->assertInstanceOf(HighCard::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid HighCard (2,4,6,7,10)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(2);
        $this->cardStubs[1]->method("getRank")->willReturn(4);
        $this->cardStubs[2]->method("getRank")->willReturn(6);
        $this->cardStubs[3]->method("getRank")->willReturn(7);
        $this->cardStubs[4]->method("getRank")->willReturn(10);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is still a valid HighCard (8,8,8,8,8)
     */
    public function testCheckHandValid2(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(8);
        $this->cardStubs[1]->method("getRank")->willReturn(8);
        $this->cardStubs[2]->method("getRank")->willReturn(8);
        $this->cardStubs[3]->method("getRank")->willReturn(8);
        $this->cardStubs[4]->method("getRank")->willReturn(8);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }
}
