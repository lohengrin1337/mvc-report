<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class ThreeOfAKind.
 */
class ThreeOfAKindTest extends TestCase
{
    private ThreeOfAKind $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new ThreeOfAKind();

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
        $this->assertInstanceOf(ThreeOfAKind::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid ThreeOfAKind (3,3,3,7,7)
     */
    public function testCheckHandValid(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->cardStubs[$i]->method("getRank")->willReturn(5);
        }
        $this->cardStubs[3]->method("getRank")->willReturn(7);
        $this->cardStubs[4]->method("getRank")->willReturn(7);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid ThreeOfAKind (5,5,7,7,3)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(5);
        $this->cardStubs[1]->method("getRank")->willReturn(5);
        $this->cardStubs[2]->method("getRank")->willReturn(7);
        $this->cardStubs[3]->method("getRank")->willReturn(7);
        $this->cardStubs[4]->method("getRank")->willReturn(3);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
