<?php

namespace App\Controller;

use \Twig\Environment;
use App\Service\PdfGeneratorService;
use App\Entity\OrdreRoute;
use App\Form\OrdreRouteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class OrdreRouteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ordreRoute', name: 'ordreRoute_index')]
    public function index(): Response
    {
        $ordreRoute = $this->entityManager->getRepository(OrdreRoute::class)->findAll();

        return $this->render('ordreRoute/index.html.twig', [
            'ordreRoute' => $ordreRoute,
        ]);
    }

    #[Route("/ordreRoute/new", name: "ordreRoute_new")]
    public function new(Request $request): Response
    {
        $ordreRoute = new OrdreRoute();
        $form = $this->createForm(OrdreRouteType::class, $ordreRoute);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ordreRoute);
            $this->entityManager->flush();

            $this->addFlash('success', 'OrdreRoute created successfully.');

            return $this->redirectToRoute('ordreRoute_index');
        }

        return $this->render('ordreRoute/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/ordreRoute/{id}", name: "ordreRoute_show")]
    public function show(OrdreRoute $ordreRoute): Response
    {
        return $this->render('ordreRoute/show.html.twig', ['ordreRoute' => $ordreRoute]);
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
}
