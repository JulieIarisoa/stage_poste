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

    public function orDeuxDate($date1, $date2): float
    {
        // Créer le QueryBuilder
        $qb = $this->createQueryBuilder('b')
            // Jointure avec l'entité OrdreDeRoute pour accéder à `duree_mission`
            ->innerJoin('App\Entity\Bse', 'o', 'WITH', 'b.matricule = o.matricule')
            // Jointure avec l'entité Missionnaire pour accéder à `taux_journalier`
            ->innerJoin('App\Entity\User', 'm', 'WITH', 'b.matricule = m.matricule')
            // Calcul de la somme de (duree_mission * taux_journalier)
            ->select('SUM(o.duree_mission * m.taux_journalier) as somme')
            // Condition de date: les entrées doivent être dans la plage du mois dernier
            ->where('o.date_bse BETWEEN :startDate AND :endDate')
            // Définir les valeurs pour la plage de dates (1 mois avant aujourd'hui)
            ->setParameter('startDate', \DateTime::createFromFormat('d/m/Y', $date1))
            ->setParameter('endDate', \DateTime::createFromFormat('d/m/Y', $date2));

            try {
                // Exécuter la requête et récupérer le résultat
                $result = $qb->getQuery()->getSingleScalarResult();
                // Retourner le résultat ou 0 si aucun résultat
                return $result ? (float)$result : 0.0;
            } catch (\Doctrine\ORM\NoResultException $e) {
                // Retourner 0.0 si aucune donnée n'est trouvée
                return 0.0;
            } catch (\Exception $e) {
                // Ajouter un log ou une gestion d'erreur plus spécifique
                // Log the exception if necessary
                return 0.0;
            }
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
