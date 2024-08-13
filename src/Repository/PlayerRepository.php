<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * Get all players ordered by name asc
     * 
     * @return Player[] Returns an array of Player objects
     */
    public function getAllSortedByName(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Get a player by level
     * 
     * @param int $level
     * @return Player|null Returns a Player object or null
     */
    public function getPlayerByLevel(int $level): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.level = :level')
            ->setParameter('level', $level)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
