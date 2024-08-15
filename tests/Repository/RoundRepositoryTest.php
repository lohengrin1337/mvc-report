<?php

namespace App\Repository;

use DateTime;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test cases for RoundRepository with sqlite db
 */
class RoundRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private RoundRepository $roundRepo;
    private Round $round1;
    private Round $round2;

    protected function setup(): void
    {
        // boot symfony kernel
        self::bootKernel();

        // set entity manager and player repository
        $this->entityManager = self::getContainer()->get('doctrine')->getManager(); // @phpstan-ignore-line
        $this->roundRepo = $this->entityManager->getRepository(Round::class);

        // set up schema tool
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema(
            $this->entityManager->getMetadataFactory()->getAllMetadata()
        );

        // create a player
        $player = new Player();
        $player->setName("Player");

        // create 2 scores with total of 30 and 25
        $score1 = new Score();
        $score1->setHandScore("row1", 30);
        $score2 = new Score();
        $score2->setHandScore("row1", 15);
        $score2->setHandScore("col3", 10);

        // create two rounds
        $this->round1 = new Round();
        $this->round1->setRoundData(
            $player,
            new Board(),
            $score1,
            new DateTime("now - 5 minutes"),
            new DateTime("now - 2 minutes"), // 2 min ago
            new DateTime("00:03:00")
        );

        $this->round2 = new Round();
        $this->round2->setRoundData(
            $player,
            new Board(),
            $score2,
            new DateTime("now - 4 minutes"),
            new DateTime("now - 1 minutes"), // 1 min ago
            new DateTime("00:03:00")
        );

        // persist player and rounds
        $this->entityManager->persist($player);
        $this->entityManager->persist($this->round1);
        $this->entityManager->persist($this->round2);
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
        $this->assertInstanceOf(RoundRepository::class, $this->roundRepo);
    }



    /**
     * Get top rounds ordered by score desc
     */
    public function testGetTopRounds(): void
    {
        $rounds = $this->roundRepo->getTopRounds();

        $this->assertSame($this->round1, $rounds[0]);
        $this->assertSame($this->round2, $rounds[1]);
        $this->assertCount(2, $rounds);
    }



    /**
     * Get latest rounds ordered by finish
     */
    public function testGetLatestRounds(): void
    {
        $rounds = $this->roundRepo->getLatestRounds();

        $this->assertSame($this->round2, $rounds[0]);
        $this->assertSame($this->round1, $rounds[1]);
        $this->assertCount(2, $rounds);
    }
}
