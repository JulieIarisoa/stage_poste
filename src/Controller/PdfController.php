<?php

namespace App\Controller;

use \Twig\Environment;
use App\Service\PdfGeneratorService;
use App\Entity\OrdreRoute;
use App\Entity\Credit;
use App\Repository\BseRepository;
use App\Form\OrdreRouteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Bse;
use App\Entity\User;
use App\Form\BseType;
use App\Form\BsePayeType;
use App\Form\BseValideType;
use App\Entity\Bst;
use App\Entity\Payment;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PdfController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }


    #[Route('/output-pdf-credit', name: 'pdf_credit')]
    public function outputCredit(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
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
        
        $dataBse1 = $this->entityManager->createQueryBuilder();
        $dataBse1->select(' d.date_bse as dateBse, COUNT(d.id) AS total')
            ->from(Bse::class, 'd')
            ->where('d.date_bse BETWEEN :startDate AND :endDate')
            ->groupBy('d.date_bse')
            ->orderBy('d.date_bse','ASC')
            ->setParameter('startDate', $date_6moi_1)
            ->setParameter('endDate', $date_6moi_2);
    
        // Execute the query to get the data array
        $queryResult = $dataBse1->getQuery()->getResult();
    
    // Now pass the result (array) to the prepareChartData function
        $dataBse = $this->prepareChartData($queryResult, 'dateBse', 'total');
    
        //$dataBst = $this->prepareChartData($bstData, 'dateBst', 'Id');  // Exemple : ajustez les clés en fonction des données réelles
        //$dataOrdreRoute = $this->prepareChartData($ordreRouteData, 'NumOr', 'DureeDeplacement');
        //$dataPayment = $this->prepareChartData($paymentData, 'TauxPayer', 'Id');  // Exemple : ajustez en fonction de vos données
    
    
    
        $Credit = $this->entityManager->getRepository(Credit::class)->findAll();


        $htmlContent = $twig->render('pdf/credit.html.twig', [
            'credit' => $Credit,'user' => $user,
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

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }


    #[Route('/output-pdf-or', name: 'pdf_or')]
    public function outputOr(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $htmlContent = $twig->render('pdf/orpdf.html.twig',);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }

    #[Route('/output-pdf-stat', name: 'pdf_stat')]
    public function statout(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {

        ///Calcule depense dans 1 mos //
    

        $htmlContent = $twig->render('pdf/statistique.html.twig', [
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

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }
    
    /**
 * Prépare les données pour un graphique à partir d'une entité donnée.
 *
 *@param array $data
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
