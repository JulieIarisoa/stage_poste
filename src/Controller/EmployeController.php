<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Form\EmployeSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EmployeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/employe', name: 'employe_index')]
    public function index(): Response
    {
        $employe = $this->entityManager->getRepository(Employe::class)->findAll();
        $employeSearch = $this->entityManager->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employe' => $employe,
            'employeSearch'=> json_encode($employeSearch), 
        ]);
    }

    #[Route("/employe/new", name: "employe_new")]
    public function new(Request $request): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($employe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Employe created successfully.');

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/employe/{id}", name: "employe_show")]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', ['employe' => $employe]);
    }

    #[Route("/employe/{id}/edit", name: "employe_edit")]
    public function edit(Request $request, Employe $employe): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Employe updated successfully.');

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/edit.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route("/employe/{id}/delete", name: "employe_delete")]
    public function delete(Employe $employe): Response
    {
        $this->entityManager->remove($employe);
        $this->entityManager->flush();

        $this->addFlash('success', 'Employe deleted successfully.');

        return $this->redirectToRoute('employe_index');
    }

    #[Route('/employe/search', name: 'employe_search')]
    public function search(Request $request, EmployeRepository $employeRepository): JsonResponse
    {
        $query = $request->query->get('q');

        if ($query) {
            // Rechercher les employés par nom ou prénom
            $employes = $employeRepository->findBySearchTerm($query);
        } else {
            $employes = [];
        }

        // Retourner les résultats sous forme de JSON
        return new JsonResponse(array_map(function($employe) {
            return [
                'nom' => $employe->getNom() ?: 'Inconnu',
                'prenom' => $employe->getPrenom() ?: 'Inconnu',
            ];
        }, $employes));
    }
}
