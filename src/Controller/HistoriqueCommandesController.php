<?php

namespace App\Controller;

use App\Entity\DetailsCommande;
use App\Repository\DetailsCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueCommandesController extends AbstractController
{
    #[Route('/historique/commandes', name: 'app_historique_commandes')]
    public function index(SessionInterface $session, DetailsCommandeRepository $detailsCommande): Response
    {
        /**
         * @var $user
         */
        $user = $this->getUser();
        $commande = $session->get('commande', []);
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Si vous avez besoin d'accéder à toutes les commandes de l'utilisateur, utilisez $user->getCommandes() au lieu de $detailsCommande
        $commandesHistorique = $user->getCommande();
        $adresseHistorique = $user->getAdresses();
        // Récupérez les détails de la commande pour l'utilisateur actuel à l'aide du repository
        $details = $detailsCommande->findByJoin($commandesHistorique);
        // dd($details);
        // Organisez les détails par commande
        $commandesAvecDetails = [];
        foreach ($details as $detail) {
            $commandeId = $detail->getCommande()->getId();

            // Créez un tableau pour chaque commande s'il n'existe pas encore
            if (!isset($commandesAvecDetails[$commandeId])) {
                $commandesAvecDetails[$commandeId] = [
                    'commande' => $detail->getCommande(),
                    'details' => [],
                    'adresse' => []
                ];
            }

            // Ajoutez le détail à la commande correspondante
            $commandesAvecDetails[$commandeId]['details'][] = $detail;
        }

        // Ajouter les adresses de chaque commande

        foreach ($commandesAvecDetails as $index => $commande) {
            $commandeUser = $commande['commande']->getClient();

            foreach ($adresseHistorique as $adresse) {
                if ($adresse->getClient()->getId() == $commandeUser->getId()) {
                    $commandesAvecDetails[$index]['adresse'] = $adresse;
                }
            }
        }
        // unset($commande);

        dd($adresseHistorique);

        return $this->render('historique_commandes/historique.html.twig', [
            'commandesAvecDetails' => $commandesAvecDetails,
            'user' => $user,
        ]);
    }
}
