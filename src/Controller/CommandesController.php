<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        // Calculez le total
        $total = 0;
        foreach ($panier as $plante) {
            $total += $plante['price'] * $plante['quantity'];
        }

        // Enregistrez le total dans la session
        $session->set('total_general', $total);

        return $this->render('commandes/commandes.html.twig', [
            'controller_name' => 'CommandesController',
            'commandes' => $panier,
            'total' => $total
        ]);
    }
}