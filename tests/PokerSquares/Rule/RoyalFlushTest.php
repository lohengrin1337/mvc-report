<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class RoyalFlush.
 */
class RoyalFlushTest extends TestCase
{
    private RoyalFlush $rule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->rule = new RoyalFlush();

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
        $this->assertInstanceOf(RoyalFlush::class, $this->rule);
    }



    /**
     * Check a hand of card stubs is a valid RoyalFlush (10,11,12,13,1 of cloves)
     */
    public function testCheckHandValid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(10);
        $this->cardStubs[1]->method("getRank")->willReturn(11);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(1);
        $this->cardStubs[0]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[1]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[2]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[3]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[4]->method("getSuit")->willReturn("cloves");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid RoyalFlush (10,11,12,13,9 of cloves)
     */
    public function testCheckHandInvalid(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(10);
        $this->cardStubs[1]->method("getRank")->willReturn(11);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(9);
        $this->cardStubs[0]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[1]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[2]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[3]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[4]->method("getSuit")->willReturn("cloves");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }



        /**
     * Check a hand of card stubs is also NOT a valid RoyalFlush (10,11,12,13,1 of cloves and spades)
     */
    public function testCheckHandInvalid2(): void
    {
        $this->cardStubs[0]->method("getRank")->willReturn(10);
        $this->cardStubs[1]->method("getRank")->willReturn(11);
        $this->cardStubs[2]->method("getRank")->willReturn(12);
        $this->cardStubs[3]->method("getRank")->willReturn(13);
        $this->cardStubs[4]->method("getRank")->willReturn(1);
        $this->cardStubs[0]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[1]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[2]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[3]->method("getSuit")->willReturn("cloves");
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");

        $res = $this->rule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
