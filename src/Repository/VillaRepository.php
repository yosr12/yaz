<?php

namespace App\Repository;

use App\Entity\Villa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Villa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Villa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Villa[]    findAll()
 * @method Villa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VillaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Villa::class);
    }

    // /**
    //  * @return Villa[] Returns an array of Villa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Villa
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
