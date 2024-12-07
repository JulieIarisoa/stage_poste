<?php

namespace App\Controller;

use App\Entity\Bse;
use App\Entity\Payment;
use App\Form\BsePayeType;
use App\Form\PaymentType;
use App\Repository\BseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PaymentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository,)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }

    #[Route('/payment', name: 'payment_index')]
    public function index(): Response
    {
        $Mission = $this->BseRepository->payement();

        return $this->render('payment/index.html.twig', [
            'Mission' => $Mission,
        ]);
    }


    
    #[Route('/payment/bst', name: 'bst_payement')]
    public function payerBst(): Response
    {
        //$list_bst = $this->entityManager->getRepository(Bse::class)->findBy(['etat_validation'=>'accepte','etat'=>'Ordre de route avec BST','code_postale_payment_bst'=> null]);

        $list_bst = $this->BseRepository->payementBst();

        return $this->render('payment/payerBst.html.twig', [
            'list_bst' => $list_bst,
        ]);
    }



    #[Route('/payment/or', name: 'or_payement')]
    public function payerOr(): Response
    {

        $list_or = $this->BseRepository->payementOr();
    

        return $this->render('payment/payerOr.html.twig', [
            'list_or' => $list_or,
        ]);
    }


    #[Route("/payment/new", name: "payment_new")]
    public function new(Request $request): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            $this->addFlash('success', 'payment created successfully.');

            return $this->redirectToRoute('bse_paye');
        }

        return $this->render('payment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/payment/{id}", name: "payment_show")]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', ['payment' => $payment]);
    }

    #[Route("/payment/{id}/bst", name: "payment_edit")]
    public function edit(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BstPayeType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'payment updated successfully.');

            return $this->redirectToRoute('payment_index');
        }

        return $this->render('payment/bst.html.twig', [
            'form' => $form->createView(),
            'payment' => $payment,
        ]);
    }

    #[Route("/payment/{id}/delete", name: "payment_delete")]
    public function delete(Payment $payment): Response
    {
        $this->entityManager->remove($payment);
        $this->entityManager->flush();

        $this->addFlash('success', 'payment deleted successfully.');

        return $this->redirectToRoute('payment_index');
    }


    #[Route("/ordreRoute/{id}/paye", name: "OrdreRoute_paye")]
    public function paye(Request $request, Bse $bse): Response
    {
        $form = $this->createForm(BsePayeType::class, $bse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Bse updated successfully.');

            return $this->redirectToRoute('payement_index');
        }

        return $this->render('ordreRoute/paye.html.twig', [
            'payementOr' => $form->createView(),
            'bse' => $bse,
        ]);
    }
    
}
