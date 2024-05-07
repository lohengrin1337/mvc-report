<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }



    /**
     * Get all products with a minimum value of $value
     * 
     * @param int $value - the min val
     * @return Product[] Returns an array of Product objects
     */
    public function findByMinVal($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.value >= :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    /**
     * Get all products with a minimum value of $value
     * 
     * @param int $value - the min val
     * @return [][] Returns a resultset
     */
    public function findByMinVal2($value): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * FROM product
            WHERE value >= :val
            ORDER BY id ASC
        ";

        $res = $conn->executeQuery($sql, ["val" => $value]);

        return $res->fetchAllAssociative();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
