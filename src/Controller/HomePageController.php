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

class HomePageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
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
    /*$date_renouvellement = $this->entityManager->createQueryBuilder();
    $date_renouvellement->select('c.date_renouvellement')
                ->from(Credit::class, 'c')
                ->orderBy('c.id', 'DESC')  // Applique l'ordre par id DESC
                ->setMaxResults(1);
    $date_renouvel = $date_renouvellement->getQuery()->getSingleScalarResult();*/


    // Récupérer toutes les données des entités
    //$bseData = $this->entityManager->getRepository(Bse::class)->findAll();
    $bstData = $this->entityManager->getRepository(Bst::class)->findAll();
    $ordreRouteData = $this->entityManager->getRepository(OrdreRoute::class)->findAll();
    //$paymentData = $this->entityManager->getRepository(Payment::class)->findAll();

    // Récupérer les utilisateurs et les crédits
    $user = $this->entityManager->getRepository(User::class)->findAll();
    $credit = $this->entityManager->getRepository(Credit::class)->findAll();

    // Préparer les données pour les graphiques// Example of fetching Bse data (assuming you have an entity called 'Bse')
    $bseData = $this->entityManager->getRepository(Bse::class)->findAll();

    // Now you can call the method with the initialized variable
    $dataBse = $this->prepareChartData($bseData, 'DateBse', 'id');
    $dataBst = $this->prepareChartData($bstData, 'DateBst', 'Id');  // Exemple : ajustez les clés en fonction des données réelles
    $dataOrdreRoute = $this->prepareChartData($ordreRouteData, 'NumOr', 'DureeDeplacement');
    //$dataPayment = $this->prepareChartData($paymentData, 'TauxPayer', 'Id');  // Exemple : ajustez en fonction de vos données

    return $this->render('home_page/index.html.twig', [
        'controller_name' => 'HomePageController',
        'user' => $user,
        //'bse' => $bseData,
        'credit' => $credit,
        'dataBse' => json_encode($dataBse),
        'dataBst' => json_encode($dataBst),
        'dataOrdreRoute' => json_encode($dataOrdreRoute),
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
private function prepareChartData(array $data, string $labelKey, string $valueKey): array
{
    $labels = [];
    $values = [];

    foreach ($data as $item) {
        $labels[] = $item->{'get' . $labelKey}();  // Récupère dynamiquement la valeur du label
        $values[] = $item->{'get' . $valueKey}();  // Récupère dynamiquement la valeur
    }

    return [
        'labels' => $labels,
        'values' => $values,
    ];
}

}
