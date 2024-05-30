<?php

namespace App\PokerSquares\Rule;

use App\Card\CardInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Flush.
 */
class FlushTest extends TestCase
{
    private Flush $flushRule;

    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->flushRule = new Flush();

        for ($i=0; $i < 5; $i++) { 
            $cardStub = $this->createStub(CardInterface::class);
            $cardStub->method("getSuit")->willReturn("hearts");
            $this->cardStubs[] = $cardStub;
        }
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Flush::class, $this->flushRule);
    }



    /**
     * Check a hand of card stubs is a valid flush
     */
    public function testCheckHandValid(): void
    {
        $res = $this->flushRule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid flush
     */
    public function testCheckHandInvalid(): void
    {
        // change one of the stubs
        $this->cardStubs[0]->method("getSuit")->willReturn("diamonds");

        $res = $this->flushRule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
