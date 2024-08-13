<?php

namespace App\Repository;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test cases for PlayerRepository with sqlite db
 */
class PlayerRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;
    private PlayerRepository $playerRepo;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set entity manager and player repository
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->playerRepo = $this->entityManager->getRepository(Player::class);

        // set up schema tool
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema(
            $this->entityManager->getMetadataFactory()->getAllMetadata()
        );

        // create two players
        $humanPlayer = (new Player())
            ->setName("Human Player");
        $cpuPlayer = (new Player())
            ->setName("Cpu Player")
            ->setType("cpu")
            ->setLevel(2);

        // persist players
        $this->entityManager->persist($humanPlayer);
        $this->entityManager->persist($cpuPlayer);
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
        $this->assertInstanceOf(PlayerRepository::class, $this->playerRepo);
    }



    /**
     * Get all (2) players sorted by name
     */
    public function testGetAllSortedByName(): void
    {
        $players = $this->playerRepo->getAllSortedByName();

        $this->assertEquals("Cpu Player", $players[0]->getName());
        $this->assertEquals("Human Player", $players[1]->getName());
        $this->assertCount(2, $players);
    }



    /**
     * Get one or no player by level
     */
    public function testGetPlayerByLevel(): void
    {
        // get the cpu player
        $player = $this->playerRepo->getPlayerByLevel(2);
        $this->assertEquals("Cpu Player", $player->getName());

        // get no player
        $player = $this->playerRepo->getPlayerByLevel(1);
        $this->assertNull($player);
    }
}
