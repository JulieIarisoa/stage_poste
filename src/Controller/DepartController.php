<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Form\DepartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BseRepository;

class DepartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository )
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }

    #[Route('/depart', name: 'app_depart')]
    public function index(): Response
    {
        $Depart = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','lieu_depart_missionnaire' => Null]);
        //$Depart = $this->BseRepository->depart();
    
        return $this->render('depart/index.html.twig', [
            'depart' => $Depart,
        ]);
    }
    
    
    #[Route("/depart/{id}", name: "depart_show")]
    public function show(Bse $depart): Response
    {
        return $this->render('depart/show.html.twig', ['depart' => $depart]);
    }

    #[Route("/depart/{id}/edit", name: "depart_edit")]
    public function edit(Request $request, Bse $depart): Response
    {
        $form = $this->createForm(DepartType::class, $depart);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'depart updated successfully.');

            return $this->redirectToRoute('depart_index');
        }

        return $this->render('depart/edit.html.twig', [
            'departModifier' => $form->createView(),
            'depart' => $depart,
        ]);
    }
}
