<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Credit;
use App\Entity\Bse;
use App\Entity\Bst;
use App\Repository\BseRepository;
use App\Entity\OrdreRoute;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\DBAL\Connection;

class HomePageController extends AbstractController
{
    private $entityManager;
    private $connection;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
        $this->connection = $connection;
    }
    
    #[Route('/page', name: 'app_home_page')]
    public function index(): Response
{
    ///Calcule depense dans 1 mos //
    $date_1moi_2 = new \DateTime(); 
    $date_1moi_1 = new \DateTime(); 
    $date_1moi_1 = $date_1moi_1->modify('-1 month');


    $depense_or_1mois = $this->BseRepository->orDeuxDate($date_1moi_1->format('d/m/Y'), $date_1moi_2->format('d/m/Y'));


    $somme_depense_bst = $this->entityManager->createQueryBuilder();
    $somme_depense_bst->select('SUM(c.depense_bst)')
                        ->where('c.date_bse BETWEEN :startDate AND :endDate')
                        // Utiliser directement le format Y-m-d pour les paramètres
                        ->setParameter('startDate', $date_1moi_1->format('Y-m-d'))
                        ->setParameter('endDate', $date_1moi_2->format('Y-m-d'))
                        ->from(Bse::class, 'c');
    $resultat_somme_bst_1mois = $somme_depense_bst->getQuery()->getSingleScalarResult();
    



    ///calcule dépense dans 6mois
    $date_6moi_2 = new \DateTime(); 
    $date_6moi_1 = new \DateTime(); 
    $date_6moi_1 = $date_6moi_1->modify('-6 month');


    $depense_or_6mois = $this->BseRepository->orDeuxDate($date_6moi_1->format('d/m/Y'), $date_6moi_2->format('d/m/Y'));


    $somme_depense_bst_6mois = $this->entityManager->createQueryBuilder();
    $somme_depense_bst_6mois->select('SUM(c.depense_bst)')
                        ->where('c.date_bse BETWEEN :startDate AND :endDate')
                        // Utiliser directement le format Y-m-d pour les paramètres
                        ->setParameter('startDate', $date_6moi_1->format('Y-m-d'))
                        ->setParameter('endDate', $date_6moi_2->format('Y-m-d'))
                        ->from(Bse::class, 'c');
    $resultat_somme_bst_6mois = $somme_depense_bst->getQuery()->getSingleScalarResult();







    $credit_initial = $this->entityManager->createQueryBuilder();
    $credit_initial->select('c.credit_initial')
                ->from(Credit::class, 'c')
                ->orderBy('c.id', 'DESC')  // Applique l'ordre par id DESC
                ->setMaxResults(1);
    $credit_renouveler = $credit_initial->getQuery()->getSingleScalarResult();





    ///somme des tous les dépense

    $date_tous = new \DateTime('01-01-1999');
    $date_aujour = new \DateTime();
    $depense_or_tous = $this->BseRepository->orDeuxDate($date_tous->format('d/m/Y'), $date_aujour->format('d/m/Y'));
    
    $bst_depense = $this->entityManager->createQueryBuilder();
    $bst_depense->select('SUM(d.depense_bst)')
                 ->from(Bse::class, 'd');
    $total_bst_depense = $bst_depense->getQuery()->getSingleScalarResult();

    $depense = $depense_or_tous + $total_bst_depense;

    $somme_credit = $this->entityManager->createQueryBuilder();
    $somme_credit->select('SUM(c.credit_initial)')
                     ->from(Credit::class, 'c');
    $total_credit = $somme_credit->getQuery()->getSingleScalarResult();

    $credit_restant = $total_credit - $depense;


    $user = $this->entityManager->getRepository(User::class)->findAll();
    $credit = $this->entityManager->getRepository(Credit::class)->findAll();

    // Préparer les données pour les graphiques// Example of fetching Bse data (assuming you have an entity called 'Bse')
    
    $date_6moi2 = new \DateTime();
    $date_6moi1 = new \DateTime();
    $date_6moi1 = $date_6moi1->modify('-6 month');

    $dataBse = $this->entityManager->createQueryBuilder();
    $dataBse->select(' d.date_bse as dateBse, COUNT(d.id) AS total')
        ->from(Bse::class, 'd')
        ->where('d.date_bse BETWEEN :startDate AND :endDate')
        ->groupBy('d.date_bse')
        ->orderBy('d.date_bse','ASC')
        ->setParameter('startDate', $date_6moi1)
        ->setParameter('endDate', $date_6moi2);

    // Execute the query to get the data array
    $queryResult = $dataBse->getQuery()->getResult();  
    
   /* $date_6moi2 = new \DateTime(); 
    $date_6moi1 = new \DateTime(); 
    $date_6moi1 = $date_6moi1->modify('-6 month');// Date actuelle - 6 mois
    
    $dataBse = $this->entityManager->createQueryBuilder();
    $dataBse->select('SUBSTRING(d.date_bse, 1, 7) AS dateBse, COUNT(d.id) AS total')
        ->from(Bse::class, 'd')
        ->where('d.date_bse BETWEEN :startDate AND :endDate')
        ->groupBy('dateBse') // Regroupe par mois (année-mois)
        ->orderBy('dateBse', 'ASC')
        ->setParameter('startDate', $date_6moi1) // Formate pour la requête
        ->setParameter('endDate', $date_6moi2); // Formate pour la requête
    
    $queryResult = $dataBse->getQuery()->getResult();*/
    

// Now pass the result (array) to the prepareChartData function
    $dataBse = $this->prepareChartData($queryResult, 'dateBse', 'total');

    return $this->render('home_page/index.html.twig', [
        'controller_name' => 'HomePageController',
        'user' => $user,
        //'bse' => $bseData,
        'credit' => $credit,
        'dataBse' => json_encode($dataBse),
        //'dataBst' => json_encode($dataBst),
        //'dataOrdreRoute' => json_encode($dataOrdreRoute),
        //'dataPayment' => json_encode($dataPayment),
            'depense_or_1mois' => $depense_or_1mois,
            'somme_depense_bst_1mois'=> $resultat_somme_bst_1mois,
            'depense_or_6mois' => $depense_or_6mois,
            'somme_depense_bst_6mois'=> $resultat_somme_bst_6mois,
            'credit_renouveler'=> $credit_renouveler,
            /*'date_renouvellement' => $date_renouvellement*/
            'date_1moi_1' => $date_1moi_1,
            'date_1moi_2' => $date_1moi_2,
            'date_6moi_1' => $date_6moi_1,
            'date_6moi_2' => $date_6moi_2,
            'depense_or_tous' => $depense_or_tous,
            'credit_restant' => $credit_restant,
    ]);
}

/**
 * Prépare les données pour un graphique à partir d'une entité donnée.
 *
 * @param array $data
 * @param string $labelKey
 * @param string $valueKey
 * @return array
 */
public function prepareChartData(array $data, string $labelKey, string $valueKey)
{
    $labels = [];
    $values = [];

    foreach ($data as $item) {
        // Supposons que item est un objet ou un tableau d'objets. Par exemple, un objet DateTime pour 'dateBse'
        
        // Si labelKey est 'dateBse' et que c'est un objet DateTime, on le convertit en chaîne
        if ($labelKey === 'dateBse' && isset($item[$labelKey])) {
            // Si c'est un objet DateTime, formattez-le en chaîne
            $labels[] = $item[$labelKey]->format('Y-m-d'); // Par exemple '2023-10-01'
        } else {
            // Sinon, utilisez la valeur brute
            $labels[] = $item[$labelKey]; // Par exemple 'total'
        }

        // Les valeurs sont supposées être déjà des nombres ou des chaînes, on les ajoute directement
        $values[] = $item[$valueKey];
    }

    return [
        'labels' => $labels,
        'values' => $values,
    ];
}



}
