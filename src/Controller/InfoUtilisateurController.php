<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class InfoUtilisateurController extends AbstractController
{
    #[Route('/info/utilisateur', name: 'app_info_utilisateur')]
    public function index(): Response
    {
        return $this->render('info_utilisateur/infosUtilisateur.html.twig', [
            'controller_name' => 'InfoUtilisateurController',
        ]);
    }
}
