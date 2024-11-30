<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Form\CreditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CreditController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/credit', name: 'credit_index')]
    public function index(): Response
    {
        $Credit = $this->entityManager->getRepository(Credit::class)->findAll();
        $dataCredit = $this->prepareChartData($Credit, 'date_renouvellement', 'credit_initial');

        return $this->render('credit/index.html.twig', [
            'credit' => $Credit,
            'dataCredit' => json_encode($dataCredit),
        ]);
    }

    #[Route("/credit/new", name: "credit_new")]
    public function new(Request $request): Response
    {
        $Credit = new Credit();
        $form = $this->createForm(CreditType::class, $Credit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($Credit);
            $this->entityManager->flush();

            $this->addFlash('success', 'Credit created successfully.');

            return $this->redirectToRoute('credit_index');
        }

        return $this->render('credit/new.html.twig', [
            'formCredit' => $form->createView(),
        ]);
    }

    #[Route("/credit/{id}", name: "credit_show")]
    public function show(Credit $Credit): Response
    {
        return $this->render('credit/show.html.twig', ['credit' => $Credit]);
    }

    #[Route("/credit/{id}/edit", name: "credit_edit")]
    public function edit(Request $request, Credit $Credit): Response
    {
        $form = $this->createForm(CreditType::class, $Credit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Credit updated successfully.');

            return $this->redirectToRoute('credit_index');
        }

        return $this->render('credit/edit.html.twig', [
            'modifierCredit' => $form->createView(),
            'credit' => $Credit,
        ]);
    }

    #[Route("/credit/{id}/delete", name: "credit_delete")]
    public function delete(Credit $Credit): Response
    {
        $this->entityManager->remove($Credit);
        $this->entityManager->flush();

        $this->addFlash('success', 'Credit deleted successfully.');

        return $this->redirectToRoute('credit_index');
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
    $values = [];
$labels = [];

foreach ($data as $item) {
    if ($item instanceof App\Entity\Credit) {
        if ($labelKey === 'date_renouvellement' && $item->getDateRenouvellement()) {
            $labels[] = $item->getDateRenouvellement()->format('Y-m-d');
        } else {
            $getter = "get" . ucfirst($labelKey);
            if (method_exists($item, $getter)) {
                $labels[] = $item->$getter();
            }
        }
    } elseif (is_array($item) && isset($item[$labelKey])) {
        $labels[] = $item[$labelKey];
    }
}

// Reste du traitement des données


    return [
        'labels' => $labels,
        'values' => $values,
    ];
}

}
