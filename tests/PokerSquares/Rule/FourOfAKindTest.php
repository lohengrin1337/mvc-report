<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class FourOfAKind.
 */
class FourOfAKindTest extends TestCase
{
    private FourOfAKind $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new FourOfAKind();

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
        $this->assertInstanceOf(FourOfAKind::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid FourOfAKind (5,5,5,5,7)
     */
    public function testCheckHandValid(): void
    {
        for ($i=0; $i < 4; $i++) { 
            $this->cardStubs[$i]->method("getRank")->willReturn(5);
        }
        $this->cardStubs[4]->method("getRank")->willReturn(7);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid FourOfAKind (5,5,5,7,7)
     */
    public function testCheckHandInvalid(): void
    {
        for ($i=0; $i < 3; $i++) { 
            $this->cardStubs[$i]->method("getRank")->willReturn(5);
        }
        $this->cardStubs[3]->method("getRank")->willReturn(7);
        $this->cardStubs[4]->method("getRank")->willReturn(7);

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
