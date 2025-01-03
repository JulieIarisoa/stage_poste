<?php

namespace App\Controller;

use \Twig\Environment;
use App\Service\PdfGeneratorService;
use App\Entity\OrdreRoute;
use App\Entity\Credit;
use App\Repository\BseRepository;
use App\Form\OrdreRouteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Bse;
use App\Entity\User;
use App\Form\BseType;
use App\Form\BsePayeType;
use App\Form\BseValideType;

class OrdreRouteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }

    #[Route('/ordreRoute', name: 'ordreRoute_index')]
    public function index(): Response
    {
          $bse = $this->entityManager->getRepository(Bse::class)->findAll();
          $bse_validation_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'en_attente','etat_payment_or' => 'en_attente']);
          $validation_accepte_non_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'accepte','etat_payment_or' => 'en_attente']);
          $validation_accepte_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'accepte','etat_payment_or' => 'paye']);
          $validation_refuse = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation_or' => 'refuse']);
       // $bse_payment_attente = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'non_paye']);
       // $bse_payment_paye = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation' => 'accepte','etat_payment' => 'paye']);

        $user = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('ordreRoute/index.html.twig', [
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

   // #[Route("/ordreRoute/new", name: "ordreRoute_new")]
    
    #[Route("/bse/new", name: "ordreRoute_new")]
    public function new(Request $request): Response
    {

        $depense_or = $this->BseRepository->totalDepense();
        $depense_bst = $this->BseRepository->totalDepenseBst();
        $credit = $this->entityManager->createQueryBuilder();
        $credit->select('SUM(d.credit_initial)')
                     ->from(Credit::class, 'd');
        $sommeCredit = $credit->getQuery()->getSingleScalarResult();


        $id = $request->get('id');
        $Taux = $this->BseRepository->Taux($id);

        $bse = new Bse();
        $form = $this->createForm(BseType::class, $bse, ['id' => $id]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bse);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse created successfully.');

            return $this->redirectToRoute('bse_index');
        }

        return $this->render('ordreRoute/new.html.twig', [
            'nouveau_or' => $form->createView(),
            'depense_or' => $depense_or,
            'depense_bst' => $depense_bst,
            'Taux' => $Taux,
            'sommeCredit' => $sommeCredit,
        ]);
    }


    #[Route("/bse/new_missionnaire", name: "ordreRoute_new_missionnaire")]
    public function new_missionnaire(Request $request): Response
    {

        $depense_or = $this->BseRepository->totalDepense();
        $depense_bst = $this->BseRepository->totalDepenseBst();
        $credit = $this->entityManager->createQueryBuilder();
        $credit->select('SUM(d.credit_initial)')
                     ->from(Credit::class, 'd');
        $sommeCredit = $credit->getQuery()->getSingleScalarResult();


        $id = $request->get('id');
        $Taux = $this->BseRepository->Taux($id);

        $bse = new Bse();
        $form = $this->createForm(BseType::class, $bse, ['id' => $id]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bse);
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse created successfully.');

            return $this->redirectToRoute('succes_index');
        }

        return $this->render('ordreRoute/new_missionnaire.html.twig', [
            'nouveau_or' => $form->createView(),
            'depense_or' => $depense_or,
            'depense_bst' => $depense_bst,
            'Taux' => $Taux,
            'sommeCredit' => $sommeCredit,
        ]);
    }


    #[Route("/ordreRoute/{id}/edit", name: "ordreRoute_edit")]
    public function edit(Request $request, OrdreRoute $ordreRoute): Response
    {
        $form = $this->createForm(OrdreRouteType::class, $ordreRoute);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'OrdreRoute updated successfully.');

            return $this->redirectToRoute('ordreRoute_index');
        }

        return $this->render('ordreRoute/edit.html.twig', [
            'form' => $form->createView(),
            'ordreRoute' => $ordreRoute,
        ]);
    }

    #[Route("/ordreRoute/{id}/delete", name: "ordreRoute_delete")]
    public function delete(OrdreRoute $ordreRoute): Response
    {
        $this->entityManager->remove($ordreRoute);
        $this->entityManager->flush();

        $this->addFlash('success', 'OrdreRoute deleted successfully.');

        return $this->redirectToRoute('ordreRoute_index');
    }


    #[Route('/output-pdf-or', name: 'pdf_or')]
    public function output(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $htmlContent = $twig->render('pdf/orpdf.html.twig');

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }


    #[Route('/output-pdf-stat', name: 'pdf_stat')]
    public function statout(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $htmlContent = $twig->render('pdf/statistique.html.twig');

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }
}
