<?php

namespace App\Service;

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
    private EntityManagerInterface $entityManagerMock;
    private PlayerRepository $playerRepoStub;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->playerRepoStub = $this->createStub(PlayerRepository::class);

        // by default no player will be returned
        $this->playerRepoStub->method("getPlayerByLevel")->willReturn(null);
        
        $this->initService = new InitCpuPlayerService(
            $this->entityManagerMock,
            $this->playerRepoStub
        );
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(InitCpuPlayerService::class, $this->initService);
    }



    /**
     * Add no players (no missing)
     */
    public function addNoMissingPlayers(): void
    {
        // expect no persist
        $this->entityManagerMock->expects($this->never())
        ->method("persist");

        $this->entityManagerMock->expects($this->once())
        ->method("flush");

        $this->initService->addMissingPlayers();
    }



    /**
     * Add 3 missing players
     */
    public function testAddMissingPlayers(): void
    {
        $playerStub = $this->createStub(Player::class);
        $this->playerRepoStub->method("getPlayerByLevel")->willReturn($playerStub);

        // expect persist x3
        $this->entityManagerMock->expects($this->exactly(3))
        ->method("persist")
        ->with($this->isInstanceOf(Player::class));

        $this->entityManagerMock->expects($this->once())
        ->method("flush");

        $this->initService->addMissingPlayers();
    }
}
