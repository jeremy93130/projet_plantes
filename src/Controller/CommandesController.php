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
        $panier = $session->get('panier', []);
        $quantity = $request->request->get('quantity_plante');
        $total = $session->get('totalGeneral', []);
        dd($total);

        // Envoyer la valeur de 'total' à la vue
        return $this->render('commandes/commandes.html.twig', [
            'controller_name' => 'CommandesController',
            'commandes' => $panier,
            // 'total' => $total, // Ajoutez cette ligne pour passer 'total' à la vue
        ]);
    }
}