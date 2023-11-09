<?php

namespace App\Controller;

use App\Repository\PlantesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatsController extends AbstractController
{
    #[Route('/achats', name: 'app_achats')]
    public function index(PlantesRepository $plantesRepository): Response
    {
        $plantes = $plantesRepository->findAll();
        return $this->render('achats/achats.html.twig', [
            'controller_name' => 'AchatsController',
            "plante" => $plantes,
        ]);
    }
}
