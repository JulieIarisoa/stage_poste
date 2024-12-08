<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Entity\User;
use App\Form\BseType;
use App\Form\BseTypeModifier;
use App\Form\BsePayeType;
use App\Form\BstOrTypeModifier;
use App\Form\BseValideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BseRepository;

class BseController extends AbstractController
{
    private $entityManager;
    private $BseRepository;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }

    #[Route('/bse', name: 'bse_index')]
    public function index(Request $request): Response
    {
          $bse = $this->entityManager->getRepository(Bse::class)->findAll();
          
          $bse_validation_attente = $this->entityManager->getRepository(Bse::class)->createQueryBuilder('b')
            ->where('b.etat_validation = :etat_validation')
            ->setParameter('etat_validation', 'en_attente')
            ->getQuery()
            ->getResult();

          $validation_refuse = $this->entityManager->getRepository(Bse::class)->createQueryBuilder('b')
            ->where('b.etat_validation = :etat_validation')
            ->setParameter('etat_validation', 'refuse')
            ->getQuery()
            ->getResult();
       // $bse_payment_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'non_paye']);
       // $bse_payment_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'paye']);

                $user = $this->entityManager->getRepository(User::class)->findAll();




                ////////////////////////////////////////////////* statistique*///////////////
                $date_now = new \DateTime();
                $startDate = (clone $date_now)->modify('-6 months'); // Point de départ
                $totals = [];
                $dates = [];

                $matricule = $request->get('matricule');
                // Générer les périodes et exécuter les requêtes dans une boucle
                for ($i = 0; $i < 6; $i++) {
                    $endDate = (clone $startDate)->modify('+1 month');

                    $queryBuilder = $this->entityManager->createQueryBuilder();
                    $totalResult = $queryBuilder->select('COUNT(d.id) AS total')
                        ->from(Bse::class, 'd')
                        ->where('d.date_bse BETWEEN :startDate AND :endDate')
                        ->andWhere('d.matricule =:matricule')
                        ->setParameter('startDate', $startDate)
                        ->setParameter('endDate', $endDate)
                        ->setParameter('matricule', $matricule)
                        ->getQuery()
                        ->getSingleScalarResult();

                    $totals[] = $totalResult ?: 0; // Ajouter le total ou 0 par défaut
                    $dates[] = $startDate->format('Y-m-d'); // Formater la date pour l'affichage

                    // Préparer la période suivante
                    $startDate = $endDate;
                }

                // Ajouter le mois actuel
                $totals[] = $this->entityManager->createQueryBuilder()
                    ->select('COUNT(d.id) AS total')
                    ->from(Bse::class, 'd')
                    ->where('d.date_bse BETWEEN :startDate AND :endDate')
                    ->andWhere('d.matricule =:matricule')
                    ->setParameter('startDate', $startDate)
                    ->setParameter('endDate', $date_now)
                    ->setParameter('matricule', $matricule)
                    ->getQuery()
                    ->getSingleScalarResult() ?: 0;
                $dates[] = $date_now->format('Y-m-d');

                // Création du tableau combiné
                $dataBse = [];
                foreach ($dates as $index => $date) {
                    $dataBse[] = [
                        'dateBse' => $date,
                        'total' => $totals[$index],
                    ];
                }

                // Préparation des données pour le graphique
                $dataBse = $this->prepareChartData($dataBse, 'dateBse', 'total');
        /***************************************************************************** *//////////////



                $date_6moi2 = new \DateTime();
                $date_6moi1 = new \DateTime();
                $date_6moi1 = $date_6moi1->modify('-6 month');
                $dataBse6Mois = $this->entityManager->createQueryBuilder();
                $dataBse6Mois->select(' COUNT(d.id) AS total')
                    ->from(Bse::class, 'd')
                    ->where('d.date_bse BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', $date_6moi1)
                    ->setParameter('endDate', $date_6moi2);
                $queryResult = $dataBse6Mois->getQuery()->getResult(); 

        /******************************************************************************************* */

            $Bse_missionnaire = $this->entityManager->getRepository(Bse::class)->findBy(['matricule'=>$matricule]);
            $Bse_Service = $this->BseRepository->bse_service();





        return $this->render('bse/index.html.twig', [
            'bse' => $bse,
            'user' => $user,
            'bse_validation_attente'=>$bse_validation_attente,
            'validation_refuse'=>$validation_refuse,
           // 'bse_payment_attente'=>$bse_payment_attente,
           // 'bse_payment_paye'=>$bse_payment_paye,
                        'dataBse' => json_encode($dataBse),
                        'dataBse6Mois' => json_encode($dataBse6Mois),
                        'Bse_missionnaire' => $Bse_missionnaire,
                        'Bse_Service' => $Bse_Service,
        ]);
    }


    #[Route("/bse/new", name: "bse_new")]
    public function new(Request $request): Response
    {
        $id = $request->get('id');
        $bse = new Bse();
        $form = $this->createForm(BseType::class, $bse, ['id' => $id]);

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


    
    #[Route('/bse/regulariser', name: 'bse_regulariser')]
    public function regulariser(): Response
    {
        $bse_regulariser = $this->BseRepository->regulariser();

        return $this->render('bse/regulariser.html.twig', [
            'bse_regulariser' => $bse_regulariser,
        ]);
    }


    
    #[Route('/bse/nonRegulariser', name: 'bse_nonRegulariser')]
    public function nonRegulariser(): Response
    {
        $bse_nonRegulariser = $this->BseRepository->nonRegulariser();

        return $this->render('bse/nonRegulariser.html.twig', [
            'bse_nonRegulariser' => $bse_nonRegulariser,
        ]);
    }


    
    #[Route('/bse/nouveau', name: 'bse_nouveau')]
    public function nouveau(): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('bse/nouveau.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route("/bse_missionnaire/{id}", name: "bse_show_missionnaire")]
    public function showMissionnaire(Bse $bse): Response
    {
        return $this->render('bse/showMissionnaire.html.twig', ['bse' => $bse]);
    }

    #[Route("/bse/{id}/edit", name: "bse_edit")]
    public function edit(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BseTypeModifier::class, $bse);

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


    #[Route("/bse/{id}/editMissionnaire", name: "bse_edit_missionnaire")]
    public function editMissionnaire(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BseTypeModifier::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('succes_index');
        }

        return $this->render('ordreRoute/editMissionnaire.html.twig', [
            'edit_refuse' => $form->createView(),
            'bse' => $bse,
        ]);
    }

    #[Route("/bse/{id}/editMissionnaireAvecBst", name: "bse_edit_missionnaire_Avec_Bst")]
    public function editMissionnaireAvecBst(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BstOrTypeModifier::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('succes_index');
        }

        return $this->render('ordreRoute/editMissionnaireAvecBst.html.twig', [
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


    #[Route("/succes", name: "succes_index")]
    public function succes(Request $request): Response
    {

        return $this->render('bse/succes.html.twig');
    }


    /**
     * Prépare les données pour un graphique à partir d'une entité donnée.
     *
     * @param array $data
     * @param string $labelKey
     * @param string $valueKey
     * @return array
     */
    public function prepareChartData(array $data, string $labelKey, string $valueKey)
    {
        $labels = [];
        $values = [];

        foreach ($data as $item) {
            // Supposons que item est un objet ou un tableau d'objets. Par exemple, un objet DateTime pour 'dateBse'
          
            // Sinon, utilisez la valeur brute
            $labels[] = $item[$labelKey]; // P
            // Les valeurs sont supposées être déjà des nombres ou des chaînes, on les ajoute directement
            $values[] = $item[$valueKey];
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
