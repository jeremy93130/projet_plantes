<?php

namespace App\Controller;

use App\Form\PlanteSearchType;
use App\Repository\PlantesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AchatsController extends AbstractController
{
    #[Route('/achats', name: 'app_achats')]
    public function index(PlantesRepository $plantesRepository, Request $request): Response
    {
        $form = $this->createForm(PlanteSearchType::class);
        $form->handleRequest($request);
        $plantes = $plantesRepository->findAll();

        return $this->render('achats/achats.html.twig', [
            'controller_name' => 'AchatsController',
            'form' => $form->createView(),
            "plante" => $plantes,
        ]);
    }
}
