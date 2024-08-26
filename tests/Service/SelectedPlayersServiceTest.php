<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class SelectedPlayersService with sqlite db
 */
class SelectedPlayersServiceTest extends KernelTestCase
{
    private SelectedPlayersService $spService;
    private EntityManagerInterface $entityManager;
    private PlayerRepository $playerRepo;
    private SessionInterface $sessionMock;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set entity manager, player repo and session stub
        $this->entityManager = self::getContainer()->get('doctrine')->getManager(); // @phpstan-ignore-line
        $this->playerRepo = $this->entityManager->getRepository(Player::class);
        $this->sessionMock = $this->createMock(SessionInterface::class);

        // create the SelectedPlayersService instance
        $this->spService = new SelectedPlayersService(
            $this->playerRepo
        );

        // set up schema tool
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema(
            $this->entityManager->getMetadataFactory()->getAllMetadata()
        );

        // create THREE players (one stub)
        $player1 = new Player();
        $player1->setName("Player1");
        $player2 = new Player();
        $player2->setName("Player2");
        $player3Stub = $this->createStub(Player::class);
        $player3Stub->method("getId")->willReturn(999);
        $player3Stub->method("getName")->willReturn("Player3");


        // persist TWO players
        $this->entityManager->persist($player1);
        $this->entityManager->persist($player2);
        $this->entityManager->flush();

        // get the two real players (with id) from db
        $players = $this->playerRepo->findAll();

        // mock session to return all players
        $this->sessionMock
            ->method("get")
            ->with("players")
            ->willReturn([$players[0], $players[1], $player3Stub]);
    }



    protected function tearDown(): void
    {
        parent::tearDown();

        // clear db
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();

        // close entity manager
        $this->entityManager->close();
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(SelectedPlayersService::class, $this->spService);
    }



    /**
     * Get players from session stub, but exclude player 3 (not in db)
     */
    public function testGetSelectedPlayers(): void
    {
        // get the selected players
        $players = $this->spService->getSelectedPlayers($this->sessionMock);

        // assert count is 2
        $this->assertCount(2, $players);

        // get names
        $playerNames = array_map(function ($player) {
            return $player->getName();
        }, $players);

        // the two players from db, and not the third from session
        $this->assertEquals(["Player1", "Player2"], $playerNames);
    }
}
