<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PaymentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/payment', name: 'payment_index')]
    public function index(): Response
    {
        $payment = $this->entityManager->getRepository(Payment::class)->findAll();

        return $this->render('payment/index.html.twig', [
            'payment' => $payment,
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

    #[Route("/payment/{id}/edit", name: "payment_edit")]
    public function edit(Request $request, Payment $payment): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'payment updated successfully.');

            return $this->redirectToRoute('payment_index');
        }

        return $this->render('payment/edit.html.twig', [
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
}
