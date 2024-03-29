<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LegaliteController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function index(SessionInterface $session): Response
    {

        return $this->render('legalite/cgv.html.twig', [
            'controller_name' => 'CgvController',
        ]);
    }

    #[Route('/mentions', name: 'app_mentions')]
    public function mentions(SessionInterface $session): Response
    {

        return $this->render('legalite/mentions.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }
    #[Route('/qui_sommes_nous?', name: 'app_info_entreprise')]
    public function infoEntreprise(SessionInterface $session): Response
    {

        return $this->render('legalite/info_entreprise.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }
}
