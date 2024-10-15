<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Form\BseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BseController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bse', name: 'bse_index')]
    public function index(): Response
    {
        $bse = $this->entityManager->getRepository(Bse::class)->findAll();

        return $this->render('bse/index.html.twig', [
            'bse' => $bse,
        ]);
    }

    #[Route("/bse/new", name: "bse_new")]
    public function new(Request $request): Response
    {
        $bse = new Bse();
        $form = $this->createForm(BseType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bse);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse created successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bse/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/bse/{id}", name: "bse_show")]
    public function show(Bse $bse): Response
    {
        return $this->render('bse/show.html.twig', ['bse' => $bse]);
    }

    #[Route("/bse/{id}/edit", name: "bse_edit")]
    public function edit(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BseType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bse/edit.html.twig', [
            'form' => $form->createView(),
            'bse' => $bse,
        ]);
    }

    #[Route("/bse/{id}/delete", name: "bse_delete")]
    public function delete(Bse $bse): Response
    {
        $this->entityManager->remove($bse);
        $this->entityManager->flush();

        $this->addFlash('success', 'Bse deleted successfully.');

        return $this->redirectToRoute('bse_index');
    }
}
