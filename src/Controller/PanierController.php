<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\PlantesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, Request $request): Response
    {

        $panier = $session->get('panier');

        $nbArticles = 0;

        if ($panier !== null && is_array($panier)) {
            $nbArticles = count($panier);
        }

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
    public function deleteFromCard($id, SessionInterface $session): RedirectResponse
    {
        // Supprimez l'article du panier dans le repository ou toute autre logique nécessaire
        // Assurez-vous d'adapter cela à votre logique spécifique

        $articles = $session->get('panier');
        foreach($articles as $key => $article){
            if($article->getId() == $id){
                unset($articles[$key]);
            };
        }
        $session->set('panier', $articles);
        // dd($articles);
        // Retournez une redirection vers la page du panier
        return $this->redirectToRoute('app_panier');
    }

}
