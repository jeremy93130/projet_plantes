<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(SessionInterface $session, Request $request): Response
    {
        $panier = $session->get('panier', []);

        $total = $request->request->get('totalGeneral');

        $quantity = json_decode($request->request->get('quantite'), true);

        var_dump($quantity);
        
        if ($total !== null) {
            $session->set('totalGeneral', $total);
            $session->set('quantity', $quantity);
        } else {
            // Sinon, récupérez-le de la session
            $total = $session->get('totalGeneral');
            $quantity = $session->get('quantity');
        }
        // Rendez votre template et renvoyez-le dans la réponse
        return $this->render('commandes/commandes.html.twig', [
            'commandes' => $panier,
            'total' => $total,
            'quantite' => $quantity,
        ]);
    }
}