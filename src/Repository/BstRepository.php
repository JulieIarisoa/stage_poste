<?php

namespace App\Repository;

use App\Entity\Bst;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bst>
 */
class BstRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bst::class);
    }

    //    /**
    //     * @return Bst[] Returns an array of Bst objects
    //     */
    //public function findByValideBst($value): array
    //{
        //return $this->createQueryBuilder('b')
         //   ->andWhere('b.valide = :val')
          //  ->setParameter('val', $value)
           // ->orderBy('b.id', 'ASC')
           // ->setMaxResults(10)
           // ->getQuery()
           // ->getResult()
           // ;
    //}

    //    public function findOneBySomeField($value): ?Bst
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
