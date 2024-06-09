<?php

namespace App\Repository;

use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Round>
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Round::class);
    }



    /**
     * Get rounds ordered by score DESC
     * 
     * @param $limit - max num of rounds
     * @return Round[] Returns an array of Round objects
     */
    public function getTopRounds($limit = 1000): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.score', 's')
            ->orderBy('s.total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }



    /**
     * Get rounds ordered by date DESC
     * 
     * @param $limit - max num of rounds
     * @return Round[] Returns an array of Round objects
     */
    public function getLatestRounds($limit = 1000): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.finish', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Round[] Returns an array of Round objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Round
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
