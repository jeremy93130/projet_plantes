<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use App\Repository\DetailsCommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueCommandesController extends AbstractController
{
    #[Route('/historique/commandes', name: 'app_historique_commandes')]
    public function index(DetailsCommandesRepository $commandes, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $commande = $session->get('commande', []);
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        // Forcez l'initialisation de la collection
        $user->getDetailsCommandes()->initialize();
        // Boucle sur les commandes et forcez l'initialisation de la collection plantes
        foreach ($user->getDetailsCommandes() as $commandePlante) {
            $commandePlante->getPlante()->initialize();
            $commandePlante->getQuantites()->initialize();
        }

        $commandesHistorique = $user->getDetailsCommandes();
        return $this->render('historique_commandes/historique.html.twig', [
            'historicCommande' => $commandesHistorique,
            'user' => $user,
        ]);
    }
}
