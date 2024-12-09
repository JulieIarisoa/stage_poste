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
use App\Entity\Bst;
use App\Entity\Payment;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Dompdf\Options;
use Dompdf\Dompdf;

class PdfController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BseRepository $BseRepository)
    {
        $this->entityManager = $entityManager;
        $this->BseRepository = $BseRepository;
    }


    #[Route('/output-pdf-credit', name: 'pdf_credit')]
    public function outputCredit(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
            $depense = $this->BseRepository->depense();
            $credit = $this->entityManager->getRepository(Credit::class)->findAll();
        
        
            $htmlContent = $twig->render('pdf/credit.html.twig', [
                'credit' => $credit,
                'depense' => $depense,
        ]);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }


    #[Route('/output-pdf-facture', name: 'pdf_facture')]
    public function outputOr(Request $request, Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {

        $id = $request->get('id');
        $facture = $this->BseRepository->facture($id);

        $htmlContent = $twig->render('pdf/facture.html.twig',[
            'facture' => $facture,
        ]);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf'
        ]);
    }


    #[Route('/output-pdf-bordereau', name: 'pdf_bordereau')]
    public function outputBordereau(Request $request, Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {

        $id = $request->get('id');
        $bordereau = $this->BseRepository->facture($id);

        $htmlContent = $twig->render('pdf/bordereau.html.twig',[
            'bordereau' => $bordereau,
        ]);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf'
        ]);
    }


    #[Route('/output-pdf-allrapport1mois', name: 'pdf_allRapportmois')]
    public function outputAllRapport1Mois(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $date_1moi_2 = new \DateTime(); 
        $date_1moi_1 = new \DateTime(); 
        $date_1moi_1 = $date_1moi_1->modify('-1 month');

        $bseEntreDeuxDate = $this->BseRepository->bseEntreDeuxDate($date_1moi_1->format('d/m/Y'), $date_1moi_2->format('d/m/Y'));
        
        $htmlContent = $twig->render('pdf/AllRapport1Mois.html.twig',[
            'bseEntreDeuxDate' => $bseEntreDeuxDate,
        ]);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }


    #[Route('/output-pdf-etatBse6mois', name: 'pdf_etatBse6mois')]
    public function outputEtatBse6mois(Environment $twig, PdfGeneratorService $pdfGeneratorService): Response
    {
        $date_6moi_2 = new \DateTime(); 
        $date_6moi_1 = new \DateTime(); 
        $date_6moi_1 = $date_6moi_1->modify('-6 month');

        $bseEntreDeuxDate = $this->BseRepository->bseEntreDeuxDate($date_6moi_1->format('d/m/Y'), $date_6moi_2->format('d/m/Y'));
        
        $htmlContent = $twig->render('pdf/etatBse6mois.html.twig',[
            'bseEntreDeuxDate' => $bseEntreDeuxDate,
        ]);

        $content = $pdfGeneratorService->output($htmlContent);

        return new Response($content, 200, [
            'content-type' => 'application/pdf',
        ]);
    }

    /**
 * Prépare les données pour un graphique à partir d'une entité donnée.
 *
 *@param array $data
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
           
           // Si labelKey est 'dateBse' et que c'est un objet DateTime, on le convertit en chaîne
           if ($labelKey === 'dateBse' && isset($item[$labelKey])) {
               // Si c'est un objet DateTime, formattez-le en chaîne
               $labels[] = $item[$labelKey]->format('Y-m-d'); // Par exemple '2023-10-01'
           } else {
               // Sinon, utilisez la valeur brute
               $labels[] = $item[$labelKey]; // Par exemple 'total'
           }
   
           // Les valeurs sont supposées être déjà des nombres ou des chaînes, on les ajoute directement
           $values[] = $item[$valueKey];
       }
   
       return [
           'labels' => $labels,
           'values' => $values,
       ];
   }
   
   
}
