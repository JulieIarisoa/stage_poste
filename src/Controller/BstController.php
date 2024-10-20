<?php

namespace App\Controller;

use App\Entity\Bst;
use App\Entity\Employe;
use App\Form\BstType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BstController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bst', name: 'bst_index')]
    public function index(): Response
    {
        $bst = $this->entityManager->getRepository(Bst::class)->findAll();
        $employe = $this->entityManager->getRepository(Employe::class)->findAll();

        return $this->render('bst/index.html.twig', [
            'bst' => $bst,
            'employe' => $employe,
        ]);
    }

    #[Route("/bst/new", name: "bst_new")]
    public function new(Request $request): Response
    {
        $bst = new Bst();
        $form = $this->createForm(BstType::class, $bst);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bst);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bst created successfully.');

            return $this->redirectToRoute('bst_index');
        }

        return $this->render('bst/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/bst/{id}", name: "bst_show")]
    public function show(Bst $bst): Response
    {
        return $this->render('bst/show.html.twig', ['bst' => $bst]);
    }

    #[Route("/bst/{id}/edit", name: "bst_edit")]
    public function edit(Request $request, Bst $bst): Response
    {
        $form = $this->createForm(BstType::class, $bst);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bst updated successfully.');

            return $this->redirectToRoute('bst_index');
        }

        return $this->render('bst/edit.html.twig', [
            'form' => $form->createView(),
            'bst' => $bst,
        ]);
    }

    #[Route("/bst/{id}/delete", name: "bst_delete")]
    public function delete(Bst $bst): Response
    {
        $this->entityManager->remove($bst);
        $this->entityManager->flush();

        $this->addFlash('success', 'Bst deleted successfully.');

        return $this->redirectToRoute('bst_index');
    }
}
