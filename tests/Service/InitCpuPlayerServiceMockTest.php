<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class InitCpuPlayerService
 */
class InitCpuPlayerServiceMockTest extends TestCase
{
    private InitCpuPlayerService $initService;
    private MockObject $entityManagerMock;

    /** @var MockObject|PlayerRepository */
    private $playerRepoStub;

    private Player|null $player = null;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->playerRepoStub = $this->createStub(PlayerRepository::class);

        // mock getPlayerByLevel() to return $this->$player
        $this->playerRepoStub->method("getPlayerByLevel")->willReturnCallback(function () {
            return $this->player;
        });


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
        // a player will always be returned from playerrepo::getPlayerByLevel
        $this->player = $this->createStub(Player::class);

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
        // expect persist x3
        $this->entityManagerMock->expects($this->exactly(3))
            ->method("persist")
            ->with($this->isInstanceOf(Player::class));

        $this->entityManagerMock->expects($this->once())
            ->method("flush");

        $this->initService->addMissingPlayers();
    }
}
