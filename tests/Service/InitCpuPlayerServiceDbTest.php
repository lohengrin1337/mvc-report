<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test cases for class InitCpuPlayerService with sqlite db
 */
class InitCpuPlayerServiceDbTest extends KernelTestCase
{
    private InitCpuPlayerService $initService;
    private EntityManagerInterface $entityManager;
    private PlayerRepository $playerRepo;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set reset service, entity manager
        $this->entityManager = self::getContainer()->get('doctrine')->getManager(); // @phpstan-ignore-line
        $this->playerRepo = $this->entityManager->getRepository(Player::class);

        // create the InitCpuPlayerService instance
        $this->initService = new InitCpuPlayerService(
            $this->entityManager,
            $this->playerRepo,
        );

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
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(InitCpuPlayerService::class, $this->initService);
    }



    /**
     * Add the two missing cpu players
     */
    public function testAddMissingPlayers(): void
    {
        // get one human + one cpu level 2
        $oldPlayers = $this->playerRepo->findAll();

        // assert exactly 2 players
        $this->assertCount(2, $oldPlayers);

        // run init service, to add the missing two cpu players (level 1 and 3)
        $this->initService->addMissingPlayers();

        // get 4 players, old plus new
        $newPlayers = $this->playerRepo->findAll();

        // assert exactly 4 players
        $this->assertCount(4, $newPlayers);

        // assert old players are still in db
        foreach ($oldPlayers as $oldPlayer) {
            $this->assertContains($oldPlayer, $newPlayers);
        }

        // get players of level 1 and 3 (the 2 newly added)
        $newCpuPlayers = array_filter($newPlayers, function ($newPlayer) {
            $level = $newPlayer->getLevel();
            return $level === 1 || $level == 3;
        });

        // assert 2 new players
        $this->assertCount(2, $newCpuPlayers);
    }
}
