<?php

namespace App\Repository;

use App\Entity\Bse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bse>
 */
class BseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BSE::class);
    }

   /*** 
     * @return BSE[] Returns an array of BSE objects that have at least one BST or OR associated
    
    public function findBseWithOrAndBst(): array
    {
        return $this->createQueryBuilder('bse')
            ->leftJoin('bse.bst', 'bst')  // Jointure avec BST
            ->leftJoin('bse.or', 'or')    // Jointure avec OR
            ->where('bst.id IS NOT NULL OR or.id IS NOT NULL')  // Condition pour les BSE avec BST ou OR
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les BSE qui n'ont ni BST ni OR associés
    
    public function findBseWithoutOrAndBst(): array
    {
        return $this->createQueryBuilder('bse')
            ->leftJoin('bse.bst', 'bst')  // Jointure avec BST
            ->leftJoin('bse.or', 'or')    // Jointure avec OR
            ->where('bst.id IS NULL')     // Pas de BST associé
            ->andWhere('or.id IS NULL')   // Pas d'OR associé
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Bse[] Returns an array of Bse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bse
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
