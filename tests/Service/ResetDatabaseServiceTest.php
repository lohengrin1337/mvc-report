<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test cases for ResetDatabaseService with sqlite db
 */
class ResetDatabaseServiceTest extends KernelTestCase
{
    private ResetDatabaseService $resetService;
    private ?EntityManagerInterface $entityManager = null;
    private PlayerRepository $playerRepo;
    private InitCpuPlayerService $initServiceMock;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set reset service, entity manager and init service mock
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->playerRepo = $this->entityManager->getRepository(Player::class);
        $this->initServiceMock = $this->createMock(InitCpuPlayerService::class);;

        // create the ResetDatabaseService instance
        $this->resetService = new ResetDatabaseService(
            $this->entityManager,
            $this->playerRepo,
            $this->initServiceMock
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
        $this->entityManager = null;
    }



    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(ResetDatabaseService::class, $this->resetService);
    }



    /**
     * Reset db by removing the current two players, and adding 3 new cpu players
     */
    public function testResetDatabase(): void
    {
        // get the current players (2)
        $currentPlayers = $this->playerRepo->findAll();
        $this->assertCount(2, $currentPlayers);

        // expect a method call
        $this->initServiceMock->expects($this->once())
            ->method("addMissingPlayers");

        // reset the database
        $response = $this->resetService->reset();

        // assert response
        $this->assertEquals(
            [
                "message" => "2 players and 0 rounds were " .
                "successfully removed, and cpu players were recreated"
            ],
            $response
        );

        // get new players (shold be none, since initService is a mock)
        $newPlayers = $this->playerRepo->findAll();
        $this->assertEmpty($newPlayers);
    }



    /**
     * throw exception with initServiceMock, and assert error response
     */
    public function testResetDatabaseFail(): void
    {
        $this->initServiceMock
            ->method("addMissingPlayers")
            ->will($this->throwException(new \Exception("error")));

        // try to reset the database
        $response = $this->resetService->reset();

        // assert response
        $this->assertEquals(
            [
                "message" => "Something went wrong",
                "error" => "error",
            ],
            $response
        );
    }
}
