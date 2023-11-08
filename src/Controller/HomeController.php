<?php

namespace App\Controller;

use App\Repository\PlantesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PlantesRepository $plantesRepository): Response
    {
        $plantes = $plantesRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "plante" => $plantes,
        ]);
    }

    
    #[Route('/details/{id}', name: 'details')]
    public function show(PlantesRepository $plantesRepository, $id): Response
    {
        $plantes = $plantesRepository->find($id);

        return $this->render('details/details.html.twig', [
            "plante" => $plantes
        ]);
    }
}
