<?php

namespace App\Controller;

use App\Service\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/output-pdf', name: 'app_output_pdf')]
    public function output(PdfGeneratorService $pdfGeneratorService): Response
    {
        $content = $pdfGeneratorService->output('<h1>Hello World</h1>');

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }
}