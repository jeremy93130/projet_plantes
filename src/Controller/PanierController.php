<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\PlantesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'app_panier')]
    public function index(PlantesRepository $plantesRepository, Request $request): Response
    {
        $panier = $request->getSession()->get('panier');

        $nbArticles = count($panier);
        
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'infos' => $panier,
            'nbArticle' => $nbArticles,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(PlantesRepository $plantesRepository, SessionInterface $session, $id): Response
    {
        $plante = $plantesRepository->find($id);

        if (!$plante) {
            throw $this->createNotFoundException('Plante non trouvée');
        }

        // Récupérer le panier actuel depuis la session
        $panier = $session->get('panier', []);

        // Ajouter la plante au panier
        $panier[] = $plante;

        // Enregistrer le panier mis à jour dans la session
        $session->set('panier', $panier);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/supprimer/{id}', name: 'app_supp')]
    public function deleteFromCard(PlantesRepository $plantesRepository, Request $request, $id): Response
    {
        // Supprimez l'article du panier dans le repository ou toute autre logique nécessaire
        $plantesRepository->deleteFrom($id);


        // Redirigez simplement vers la page du panier
        return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
    }

}
