<?php

namespace App\Service;

use App\Card\CardInterface;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;


/**
 * Test cases for class InitCpuPlayerService.
 */
class InitCpuPlayerServiceTest extends TestCase
{
    private InitCpuPlayerService $initService;

    protected function setUp(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $playerRepoStub = $this->createStub(PlayerRepository::class);
        $playerStub = $this->createStub(Player::class);
        $playerRepoStub->method("getPlayerByLevel")->willReturn($playerStub);
        $this->initService = new InitCpuPlayerService($entityManagerMock, $playerRepoStub);
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(InitCpuPlayerService::class, $this->initService);
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
        for ($i=0; $i < 4; $i++) { 
            $this->cardStubs[$i]->method("getSuit")->willReturn("hearts");
        }
        $this->cardStubs[4]->method("getSuit")->willReturn("spades");

        $res = $this->flushRule->checkHand($this->cardStubs);
        $this->assertFalse($res);
    }
}
