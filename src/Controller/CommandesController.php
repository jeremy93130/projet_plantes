<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(SessionInterface $session, Request $request): Response
    {


        // Récupérer le total depuis les données POST
        $total = $request->request->get('total');

        dd($total);
        // Faites quelque chose avec le total, par exemple, enregistrez-le en base de données
        // ...

        // Renvoyer une URL dans la réponse JSON
        $url = $this->generateUrl('app_commandes');
        return $this->json(['url' => $url]);
    }

    #[Route('/commandes/{total}', name: 'app_commande_quantity')]
    public function total(SessionInterface $session, $total): Response
    {
        $panier = $session->get('panier', []);

        dd($total);

        return $this->render('commandes/commandes.html.twig', [
            'controller_name' => 'CommandesController',
            'commandes' => $panier,
        ]);
    }
}