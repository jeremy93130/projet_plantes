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
    public function index(SessionInterface $session, Request $request): Response
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
        // dd($sessionCommande);
        // Récupérer les informations nécessaires de la session ou ailleurs
        $successMessage = $session->get('success_url');
        // dd($sessionPanier);
        $nouvelleSession = [];

        foreach ($sessionCommande['commandeData'] as $produit) {
            $produitId = $produit['id'];
            $nbArticles = $produit['quantite'];

            // Stocker le nombre d'articles dans la nouvelle variable de session avec l'ID du produit comme clé
            $nouvelleSession['nbArticles_' . $produitId] = $nbArticles;
        }

        // Enregistrez la nouvelle variable de session avec les quantités par produit
        $anciennesQuantites = $session->get('quantites', []);
        $nouvellesQuantites = array_merge($anciennesQuantites, $nouvelleSession);
        $session->set('quantites', $nouvellesQuantites);
        // dd($session->get('quantites'));

        $user = $this->getUser();
        // dd($sessionCommande);
        return $this->render('commandes/commandes.html.twig', [
            'dataCommande' => $sessionCommande,
            'user' => $user,
            'successMessage' => $successMessage
        ]);
    }
}

