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
    private ?EntityManagerInterface $entityManager = null;
    private PlayerRepository $playerRepo;
    private SessionInterface $sessionStub;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set entity manager, player repo and session stub
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->playerRepo = $this->entityManager->getRepository(Player::class);
        $this->sessionStub = $this->createStub(SessionInterface::class);

        // create the SelectedPlayersService instance
        $this->spService = new SelectedPlayersService(
            $this->entityManager,
            $this->playerRepo,
        );

        // set up schema tool
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema(
            $this->entityManager->getMetadataFactory()->getAllMetadata()
        );

        // create THREE players (one stub)
        $player1 = (new Player())
            ->setName("Player1");
        $player2 = (new Player())
            ->setName("Player2");
        $player3Stub = $this->createStub(Player::class);
        $player3Stub->method("getId")->willReturn(3);
        $player3Stub->method("getName")->willReturn("Player3");

        // mock session to return all players
        $this->sessionStub
            ->method("get")
            ->willReturn([$player1, $player2, $player3Stub]);

        // persist TWO players
        $this->entityManager->persist($player1);
        $this->entityManager->persist($player2);
        $this->entityManager->flush();
    }



    protected function tearDown(): void
    {
        parent::tearDown();

        // clear db
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();

        // close entity manager
        $this->entityManager->close();
        $this->entityManager = null;
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
        $players = $this->spService->getSelectedPlayers($this->sessionStub);

        // assert count is 2
        $this->assertCount(2, $players);

        // get names
        $playerNames = array_map(function($player) {
            return $player->getName();
        }, $players);

        // the two players from db, and not the third from session
        $this->assertEquals(["Player1", "Player2"], $playerNames);
    }
}
