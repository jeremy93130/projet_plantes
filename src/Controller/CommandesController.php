<?php

namespace App\Controller;

use App\Repository\PlantesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(SessionInterface $session, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $session->set('commande', $data);
        // Faites quelque chose avec $data (prix total, prix et quantités des plantes)

        // Vous pouvez renvoyer une réponse JSON en fonction de vos besoins
        return new JsonResponse(['message' => 'Commande reçue avec succès!', 'redirect' => $this->generateUrl('recapp_commande'), 'data' => $data]);
    }

    #[Route('/recap', name: 'recapp_commande')]
    public function recap(SessionInterface $session): Response
    {
        $sessionCommande = $session->get('commande');

        $linksParameters = [];
        if (!$this->getUser()) {
            $linksParameters = ['errorPanier' => 'Veuillez vous connecter'];
            $url = $this->generateUrl('app_login', $linksParameters);
            return $this->redirect($url);
        }
        // dd($sessionCommande);
        // dd($sessionPanier);
        // dd($successMessage);
        $user = $this->getUser();
        // dd($sessionCommande);
        return $this->render('commandes/commandes.html.twig', [
            'dataCommande' => $sessionCommande,
            'user' => $user,
            'successMessage' => null
        ]);
    }
}
