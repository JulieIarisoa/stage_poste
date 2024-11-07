<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Credit;
use App\Entity\Bse;
use App\Entity\Bst;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/page', name: 'app_home_page')]
    public function index(): Response
{
    // Récupérer toutes les données des entités
    $bseData = $this->entityManager->getRepository(Bse::class)->findAll();
    $bstData = $this->entityManager->getRepository(Bst::class)->findAll();
    $ordreRouteData = $this->entityManager->getRepository(OrdreRoute::class)->findAll();
    $paymentData = $this->entityManager->getRepository(Payment::class)->findAll();

    // Récupérer les utilisateurs et les crédits
    $user = $this->entityManager->getRepository(User::class)->findAll();
    $credit = $this->entityManager->getRepository(Credit::class)->findAll();

    // Préparer les données pour les graphiques
    $dataBse = $this->prepareChartData($bseData, 'DateBse', 'DepenseEngage');
    $dataBst = $this->prepareChartData($bstData, 'DateBst', 'Id');  // Exemple : ajustez les clés en fonction des données réelles
    $dataOrdreRoute = $this->prepareChartData($ordreRouteData, 'NumOr', 'DureeDeplacement');
    $dataPayment = $this->prepareChartData($paymentData, 'TauxPayer', 'Id');  // Exemple : ajustez en fonction de vos données

    return $this->render('home_page/index.html.twig', [
        'controller_name' => 'HomePageController',
        'user' => $user,
        'bse' => $bseData,
        'credit' => $credit,
        'dataBse' => json_encode($dataBse),
        'dataBst' => json_encode($dataBst),
        'dataOrdreRoute' => json_encode($dataOrdreRoute),
        'dataPayment' => json_encode($dataPayment),
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
