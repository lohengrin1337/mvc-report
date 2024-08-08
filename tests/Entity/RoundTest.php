<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;
use \DateTime;


/**
 * Test cases for Round Entity.
 */
class RoundTest extends TestCase
{
    private Round $round;

    protected function setUp(): void
    {
        $player = $this->createStub(Player::class);
        $board = $this->createStub(Board::class);
        $score = $this->createStub(Score::class);
        $start = new DateTime('12:00');
        $finish = new DateTime('12:01');
        $interval = $start->diff($finish);
        $duration = (new DateTime())->setTime($interval->h, $interval->i, $interval->s);
        
        $round = new Round();
        $round->setRoundData(
            $player,
            $board,
            $score,
            $start,
            $finish,
            $duration
        );

        $this->round = $round;
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(Round::class, $this->round);
    }



    /**
     * Get id
     */
    public function testGetId(): void
    {
        $this->assertNull($this->round->getId());
    }



    /**
     * Get player
     */
    public function testGetPlayer(): void
    {
        $this->assertInstanceOf(Player::class, $this->round->getPlayer());
    }



    /**
     * Get Board
     */
    public function testGetBoard(): void
    {
        $this->assertInstanceOf(Board::class, $this->round->getBoard());
    }



    /**
     * Get Score
     */
    public function testGetScore(): void
    {
        $this->assertInstanceOf(Score::class, $this->round->getScore());
    }



    /**
     * Get Start
     */
    public function testGetStart(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->round->getStart());
    }



    /**
     * Get Finish
     */
    public function testGetFinish(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->round->getFinish());
    }



    /**
     * Get Duration
     */
    public function testGetDuration(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->round->getDuration());
    }
}
