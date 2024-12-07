<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Entity\User;
use App\Form\BseType;
use App\Form\BsePayeType;
use App\Form\BseValideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BseController extends AbstractController
{
    private $entityManager;
    private $BseRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bse', name: 'bse_index')]
    public function index(): Response
    {
          $bse = $this->entityManager->getRepository(Bse::class)->findAll();
          
          $bse_validation_attente = $this->entityManager->getRepository(Bse::class)->createQueryBuilder('b')
            ->where('b.etat_validation_or = :etat_validation_or')
            ->orWhere('b.etat_validation_bst = :etat_validation_bst')
            ->setParameter('etat_validation_or', 'en_attente')
            ->setParameter('etat_validation_bst', 'en_attente')
            ->getQuery()
            ->getResult();

          $validation_refuse = $this->entityManager->getRepository(Bse::class)->createQueryBuilder('b')
            ->where('b.etat_validation_or = :etat_validation_or')
            ->orWhere('b.etat_validation_bst = :etat_validation_bst')
            ->setParameter('etat_validation_or', 'refuse')
            ->setParameter('etat_validation_bst', 'refuse')
            ->getQuery()
            ->getResult();
       // $bse_payment_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'non_paye']);
       // $bse_payment_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'paye']);

        $user = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('bse/index.html.twig', [
            'bse' => $bse,
            'user' => $user,
            'bse_validation_attente'=>$bse_validation_attente,
            'validation_refuse'=>$validation_refuse,
           // 'bse_payment_attente'=>$bse_payment_attente,
           // 'bse_payment_paye'=>$bse_payment_paye,
        ]);
    }


    #[Route("/bse/new", name: "bse_new")]
    public function new(Request $request): Response
    {
        $id = $request->get('id');
        $bse = new Bse();
        $form = $this->createForm(BseType::class, $bse, ['id' => $id]);

        $form->handleRequest($request);

        
        
        /*
                $taux_journalier = $bse->getTauxJournalier();  // suppose que vous avez ces méthodes dans votre entité
                $duree_mission = $bse->getDureeMission();
                $credit_restant = $bse->getCreditRestant();

                // Calculer la somme des dépenses
                $somme_depense = $taux_journalier * $duree_mission;

                // Vérifier si le solde est suffisant
                if ($somme_depense > $credit_restant) {
                    // Si le solde est insuffisant, ajouter un message d'erreur et ne pas persister l'entité
                    $this->addFlash('error', 'Solde insuffisant pour couvrir la dépense.');

                    // Renvoyer la vue avec le formulaire (vous pouvez ajouter une validation ou autre action)
                    return $this->render('bse/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                // Si le formulaire est soumis et valide
                if ($form->isSubmitted() && $form->isValid()) {
                    // Persister l'entité
                    $this->entityManager->persist($bse);
                    $this->entityManager->flush();

                    // Ajouter un message de succès
                    $this->addFlash('success', 'Bse created successfully.');

                    // Rediriger vers la route bse_index (ou une autre route appropriée)
                    return $this->redirectToRoute('bse_index');
                }

                // Affichage du formulaire dans la vue
                return $this->render('bse/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }'
        }*/
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

        return $this->render('ordreRoute/edit.html.twig', [
            'edit_refuse' => $form->createView(),
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


    #[Route("/bse/{id}/valide_or", name: "bse_valide_or")]
    public function valideOr(Request $request, Bse $bse): Response
    {
        $matricule = $request->get('matricule');
        $form = $this->createForm(BseValideType::class, $bse);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['matricule' => $matricule]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bse/valideOr.html.twig', [
            'valide' => $form->createView(),
            'bse' => $bse,
            'user' => $user,
        ]);
    }



    #[Route("/bse/{id}/valide_orbst", name: "bse_valide_or_bst")]
    public function valideOrBst(Request $request, Bse $bse): Response
    {
        $matricule = $request->get('matricule');
        $form = $this->createForm(BseValideType::class, $bse);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['matricule' => $matricule]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bse/valideOrBst.html.twig', [
            'valide' => $form->createView(),
            'bse' => $bse,
            'user' => $user,
        ]);
    }

    #[Route("/bse/{id}/paye", name: "bse_paye")]
    public function paye(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BsePayeType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bse/paye.html.twig', [
            'payementOr' => $form->createView(),
            'bse' => $bse,
        ]);
    }




    /*#[Route('/bse/liste', name: 'bse_liste')]
    public function listBSE(BSERepository $bseRepository): Response
    {
        // Récupérer les BSE avec OR ou BST
        $bseWithOrAndBst = $bseRepository->findBseWithOrAndBst();

        // Récupérer les BSE sans OR ni BST
        $bseWithoutOrAndBst = $bseRepository->findBseWithoutOrAndBst();

        return $this->render('bse/liste.html.twig', [
            'bseWithOrAndBst' => $bseWithOrAndBst,
            'bseWithoutOrAndBst' => $bseWithoutOrAndBst,
        ]);
    }*/
}
