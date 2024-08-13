<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for ScoreRepository.
 */
class ScoreRepositoryTest extends TestCase
{
    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $mrStub = $this->createStub(ManagerRegistry::class);
        $boardRepo = new ScoreRepository($mrStub);
        $this->assertInstanceOf(ScoreRepository::class, $boardRepo);
    }
}
