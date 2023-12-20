<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\DetailsCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueCommandesController extends AbstractController
{
    #[Route('/historique/commandes', name: 'app_historique_commandes')]
    public function index(SessionInterface $session, DetailsCommandeRepository $detailsCommande): Response
    {
        /**
         * @var $user
         */
        $user = $this->getUser();
        $userId = $user->getId();
        $commande = $session->get('commande', []);
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Si vous avez besoin d'accéder à toutes les commandes de l'utilisateur, utilisez $user->getCommandes() au lieu de $detailsCommande
        $commandes = $detailsCommande->findAllbyUserId($userId);

        $formattedResults = [];
        foreach ($commandes as $detailsCommande) {
            $commandeObj = $detailsCommande->getCommande();
            $plante = $detailsCommande->getPlante();

            $commandeId = $commandeObj->getId();

            // Si la commande n'existe pas encore dans $formattedResults, crée une nouvelle entrée
            if (!isset($formattedResults[$commandeId])) {
                $formattedResults[$commandeId] = [
                    'commande' => $commandeObj,
                    'date_commande' => $commandeObj->getDateCommande(),
                    'nom_client' => $commandeObj->getAdresse()->getNomComplet(),
                    'adresse_livraison' => $commandeObj->getAdresse()->getAdresse(),
                    'produits' => [],
                    'total' => 0,
                ];
            }

            // Ajoute le produit à la commande existante
            $formattedResults[$commandeId]['produits'][] = [
                'produit' => $plante->getNomPlante(),
                'prix' => $plante->getPrixPlante(),
                'quantite' => $detailsCommande->getQuantite(),
            ];

            // Ajoute le prix du produit au total de la commande
            $formattedResults[$commandeId]['total'] += ($plante->getPrixPlante() * $detailsCommande->getQuantite());
        }

        // dd($formattedResults);

        return $this->render('historique_commandes/historique.html.twig', [
            'commandes' => $formattedResults,
            'user' => $user,
        ]);
    }
}
