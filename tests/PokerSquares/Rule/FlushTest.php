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

    /** @var mixed[] */
    private array $cardStubs = [];

    protected function setUp(): void
    {
        $this->flushRule = new Flush();

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
        $this->assertInstanceOf(Flush::class, $this->flushRule);
    }



    /**
     * Check a hand of card stubs is a valid flush (5 hearts)
     */
    public function testCheckHandValid(): void
    {
        foreach ($this->cardStubs as $cardStub) {
            $cardStub->method("getSuit")->willReturn("hearts");
        }

        $res = $this->flushRule->checkHand($this->cardStubs);
        $this->assertTrue($res);
    }



    /**
     * Check a hand of card stubs is NOT a valid flush (4 hearts and 1 spades)
     */
    public function testCheckHandInvalid(): void
    {
        for ($i = 0; $i < 4; $i++) {
            $this->cardStubs[$i]->method("getSuit")->willReturn("hearts");
        }
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");

        $res = $this->flushRule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
