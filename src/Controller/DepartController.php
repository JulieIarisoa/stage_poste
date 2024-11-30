<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Form\DepartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DepartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/depart', name: 'app_depart')]
    public function index(): Response
    {
        //$Depart = $this->BseRepository->departMissionnaire();
        $Depart = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'accepte','lieu_depart_missionnaire' => Null]);
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
