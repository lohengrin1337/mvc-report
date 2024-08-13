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
     * @return array<array<string|int>> Returns a resultset
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
}
