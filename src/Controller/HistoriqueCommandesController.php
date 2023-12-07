<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use App\Repository\DetailsCommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueCommandesController extends AbstractController
{
    #[Route('/historique/commandes', name: 'app_historique_commandes')]
    public function index(DetailsCommandesRepository $commandes): Response
    {
        $idUser = $this->getUser()->getId();
        $commandesHistorique = $commandes->findById($idUser);
        return $this->render('historique_commandes/historique.html.twig', [
            'historicCommande' => $commandesHistorique
        ]);
    }
}
