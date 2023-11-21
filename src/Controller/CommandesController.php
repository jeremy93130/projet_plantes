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

        if ($total !== null) {
            $session->set('totalGeneral', $total);
        } else {
            // Sinon, récupérez-le de la session
            $total = $session->get('totalGeneral');
        }
        // Rendez votre template et renvoyez-le dans la réponse
        return $this->render('commandes/commandes.html.twig', [
            'commandes' => $panier,
            'total' => $total,
        ]);
    }
}