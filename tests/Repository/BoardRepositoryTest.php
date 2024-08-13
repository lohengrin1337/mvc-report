<?php

namespace App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for BoardRepository.
 */
class BoardRepositoryTest extends TestCase
{
    /**
     * Construct object and verify instance
     */
    public function testCreateInstance(): void
    {
        $mrStub = $this->createStub(ManagerRegistry::class);
        $boardRepo = new BoardRepository($mrStub);
        $this->assertInstanceOf(BoardRepository::class, $boardRepo);
    }
}
