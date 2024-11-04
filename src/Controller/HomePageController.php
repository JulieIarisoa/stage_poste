<?php

namespace App\Controller;

use App\Entity\Bse;
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
        $bseData = $this->entityManager->getRepository(Bse::class)->findAll();
        $data = [
            'labels' => [],
            'values' => [],
        ];
        foreach ($bseData as $bse) {
            $data['labels'][] = $bse->getDateBse();
            $data['values'][] = $bse->getDepenseEngage();
        }
        /*$data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'values' => [12, 19, 3, 5]
        ];*/
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            /*'data' => json_encode($data)*/
            'data' => json_encode($data)
        ]);
    }
}
