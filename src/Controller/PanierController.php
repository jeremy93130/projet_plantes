<?php

namespace App\Controller;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
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

        $panier = $session->get('panier', []);
        // $session->remove('panier');
        $panierCookies = $request->cookies->get('panier');
        $panierCookie = json_decode($panierCookies, true);
        // dd($panierCookie);
        $session->get('totalQuantite');

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'infos' => $panier,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(Request $request, SessionInterface $session, $id, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupérer les données du panier depuis la requête JSON
        $data = json_decode($request->getContent(), true);

        // Vérifier si l'ID du produit est présent dans les données
        if (!isset($data['id'])) {
            return new JsonResponse(['message' => 'ID de plante manquant'], 400);
        }
        // Récupérer le produit depuis la base de données
        $produit = $entityManagerInterface->getRepository(Produits::class)->find($id);

        // Vérifier si le produit existe
        if (!$produit) {
            return new JsonResponse(['message' => 'La plante n\'existe pas'], 404);
        }

        // Récupérer le panier actuel depuis la session
        $panier = $session->get('panier', []);

        // Calculer la somme des quantités dans le panier

        // Vérifier si la plante est déjà dans le panier
        if (array_key_exists($id, $panier)) {
            // Le produit est déjà dans le panier
            return new JsonResponse(['doublon' => "Ce produit a déjà été ajouté au panier"]);
        }

        // Ajouter le produit au panier
        $panier[$id] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomproduit(),
            'prix' => $produit->getPrixproduit(),
            'image' => $produit->getImage(),
            'nbArticles' => $data["nbArticles"],
            'categorie' => $produit->getCategorie(),
            'lot' => $produit->getLot()
        ];

        $session->set('panier', $panier);
        
        // On ajoute la clé quantité à la session 'panier' avec la valeur de totalQuantité actuelle + nbArticles
        if (empty($panier)) {
            $totalQuantite = "";
        } else {
            $totalQuantite = array_sum(array_column($panier, 'nbArticles'));
        }
        $session->set('totalQuantite', $totalQuantite);

        $response = new Response();
        $response->headers->setCookie(new Cookie('panier', json_encode($panier)));
        $response->headers->setCookie(new Cookie('totalQuantite', json_encode($totalQuantite)));
        $response->send();

        // Retourner une réponse JSON
        return new JsonResponse(['message' => 'produit ajouté au panier avec succès', 'totalQuantite' => $totalQuantite]);
        // return $this->redirectToRoute('app_home');
    }

    #[Route('/supprimer/{id}', name: 'app_supp')]
    public function deleteFromCard($id, SessionInterface $session): JsonResponse
    {
        // Supprimez l'article du panier dans le repository ou toute autre logique nécessaire
        // Assurez-vous d'adapter cela à votre logique spécifique

        $articles = $session->get('panier');
        foreach ($articles as $key => $article) {
            if ($article['id'] == $id) {
                unset($articles[$key]);
            }
            ;
        }
        $session->set('panier', $articles);
        $totalQuantite = array_sum(array_column($articles, 'nbArticles'));
        $session->set('totalQuantite', $totalQuantite);
        // $session->remove('panier');

        $response = new Response();
        $response->headers->setCookie(new Cookie('panier', json_encode($articles)));
        $response->headers->setCookie(new Cookie('totalQuantite', json_encode($totalQuantite)));
        $response->send();

        // dd($articles);
        return new JsonResponse(['success' => true, 'totalQuantite' => $totalQuantite]);
    }
}
