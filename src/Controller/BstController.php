<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Entity\User;
use App\Form\BstOrType;
use App\Form\BstPayeType;
use App\Form\BstOrValideType;
use \Twig\Environment;
use App\Service\PdfGeneratorService;
use App\Entity\Bst;
use App\Entity\Employe;
use App\Repository\BseRepository;
use App\Form\BstType;
use App\Form\BstValideType;
use App\Entity\Credit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BstController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }

    #[Route('/bst', name: 'bst_index')]
    public function index(): Response
    {
          $bse = $this->entityManager->getRepository(Bse::class)->findAll();
          $bse_validation_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_bst' => 'en_attente','etat_payment_bst' => 'en_attente']);
          $validation_accepte_non_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_bst' => 'accepte','etat_payment_bst' => 'en_attente']);
          $validation_accepte_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_bst' => 'accepte','etat_payment_bst' => 'paye']);
          $validation_refuse = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'refuse','etat_validation_bst' => 'refuse']);
       // $bse_payment_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'non_paye']);
       // $bse_payment_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'paye']);

        $user = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('bst/index.html.twig', [
            'bse' => $bse,
            'user' => $user,
            'bse_validation_attente'=>$bse_validation_attente,
            'validation_accepte_non_paye'=>$validation_accepte_non_paye,
            'validation_accepte_paye'=>$validation_accepte_paye,
            'validation_refuse'=>$validation_refuse,
           // 'bse_payment_attente'=>$bse_payment_attente,
           // 'bse_payment_paye'=>$bse_payment_paye,
        ]);
    }
    #[Route("/bst/new", name: "bst_new")]
    public function new(Request $request): Response
    {
        $depense_or = $this->BseRepository->totalDepense();
        $depense_bst = $this->BseRepository->totalDepenseBst();
        $credit = $this->entityManager->createQueryBuilder();
        $credit->select('SUM(d.credit_initial)')
                     ->from(Credit::class, 'd');
        $sommeCredit = $credit->getQuery()->getSingleScalarResult();


        $id = $request->get('id');
        $utilisateur = $this->entityManager->createQueryBuilder();
        $utilisateur->select('c.taux_journalier AS tauxJournalier')
                     ->from(User::class, 'c')
                     ->where('c.matricule =:matricule')
                     ->setParameter('matricule', $id);
        $Taux = $utilisateur->getQuery()->getSingleScalarResult();

    

        $bse = new Bse();
        $form = $this->createForm(BstOrType::class, $bse, ['id' => $id]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bse);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse created successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('bst/new.html.twig', [
            'or_bst' => $form->createView(),
            'nouveau_or' => $form->createView(),
            'depense_or' => $depense_or,
            'depense_bst' => $depense_bst,
            'Taux' => $Taux,
            'sommeCredit' => $sommeCredit,
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


    #[Route("/bst/{id}/paye", name: "bst_paye")]
    public function paye(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BstPayeType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bst updated successfully.');

            return $this->redirectToRoute('payement_index');
        }

        return $this->render('bst/paye.html.twig', [
            'payeBst' => $form->createView(),
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


    #[Route('/output-pdf-bst', name: 'pdf_bst')]
    public function output(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $htmlContent = $twig->render('pdf/bstpdf.html.twig');

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }


    #[Route("/bstor/{id}/valide", name: "bstOr_valide")]
    public function valide(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BstOrValideType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('bst_index');
        }

        return $this->render('bse/valide.html.twig', [
            'form' => $form->createView(),
            'bse' => $bse,
        ]);
    }


}