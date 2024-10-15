<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Form\BureauType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BureauController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bureau', name: 'bureau_index')]
    public function index(): Response
    {
        $bureau = $this->entityManager->getRepository(Bureau::class)->findAll();

        return $this->render('bureau/index.html.twig', [
            'bureau' => $bureau,
        ]);
    }

    #[Route("/bureau/new", name: "bureau_new")]
    public function new(Request $request): Response
    {
        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bureau);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bureau created successfully.');

            return $this->redirectToRoute('bureau_index');
        }

        return $this->render('bureau/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/bureau/{id}", name: "bureau_show")]
    public function show(Bureau $bureau): Response
    {
        return $this->render('bureau/show.html.twig', ['bureau' => $bureau]);
    }

    #[Route("/bureau/{id}/edit", name: "bureau_edit")]
    public function edit(Request $request, Bureau $bureau): Response
    {
        $form = $this->createForm(BureauType::class, $bureau);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bureau updated successfully.');

            return $this->redirectToRoute('bureau_index');
        }

        return $this->render('bureau/edit.html.twig', [
            'form' => $form->createView(),
            'bureau' => $bureau,
        ]);
    }

    #[Route("/bureau/{id}/delete", name: "bureau_delete")]
    public function delete(Bureau $bureau): Response
    {
        $this->entityManager->remove($bureau);
        $this->entityManager->flush();

        $this->addFlash('success', 'Bureau deleted successfully.');

        return $this->redirectToRoute('bureau_index');
    }
}
