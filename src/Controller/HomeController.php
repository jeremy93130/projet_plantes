<?php

namespace App\Controller;

use App\Repository\PlantesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
       return $this->redirectToRoute('app_achats');
    }


    #[Route('/details/{id}', name: 'details')]
    public function show(PlantesRepository $plantesRepository, $id, SessionInterface $session): Response
    {
        $plantes = $plantesRepository->find($id);

        return $this->render('details/details.html.twig', [
            "plante" => $plantes
        ]);
    }
}
