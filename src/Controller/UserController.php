<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'user_index')]
    public function index(): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findAll();
    
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route("/user/new", name: "user_new")]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/user/{id}", name: "user_show")]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route("/user/{id}/edit", name: "user_edit")]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'utilisateur' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route("/user/{id}/delete", name: "user_delete")]
    public function delete(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('user_index');
    }





    #[Route('/user/ajax', name: 'user_recherche', methods: ['GET'])]
    public function searchAjax(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupération du terme de recherche passé en paramètre GET
        $searchTerm = $request->query->get('q', '');

        // Vérification que le terme de recherche n'est pas vide
        if (empty($searchTerm)) {
            return new JsonResponse([]);
        }

        // Recherche des utilisateurs dont le nom ou prénom contient le terme de recherche
        $repository = $entityManager->getRepository(User::class);
        $employes = $repository->createQueryBuilder('e')
            ->where('e.nom LIKE :searchTerm OR e.prenom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();

        // Sérialisation des résultats en JSON
        $results = [];
        foreach ($employes as $employe) {
            $results[] = [
                'id' => $employe->getId(),
                'nom' => $employe->getNom(),
                'prenom' => $employe->getPrenom(),
                'email' => $employe->getEmail(),
            ];
        }

        return new JsonResponse($results);
    }




    #[Route("/page/{id}/edit", name: "user_edit_missionaire")]
    public function editMissionnaire(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('user/editmissionnaire.html.twig', [
            'utilisateur' => $form->createView(),
            'user' => $user,
        ]);
    }
}
