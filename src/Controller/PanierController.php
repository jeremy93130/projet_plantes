<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
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
    public function index(SessionInterface $session, ProduitsRepository $produitsRepository): Response
    {

        $panier = $session->get('panier', []);

        $nbArticles = 0;

        if ($panier !== null && is_array($panier)) {
            $nbArticles = count($panier);
        }
        // dd($panier);

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'infos' => $panier,
            'nbArticle' => $nbArticles,
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
            return $this->render('details/details.html.twig', []);
        }

        // Ajouter le produit au panier
        // Vous devez adapter cela en fonction de la structure réelle de vos données
        $panier[$id] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNomproduit(),
            'prix' => $produit->getPrixproduit(),
            'image' => $produit->getImage(),
            'nbArticles' => $data["nbArticles"],
            'categorie' => $produit->getCategorie()
        ];


        $totalQuantite = array_sum(array_column($panier, 'nbArticles'));

        // On ajoute la clé quantité à la session 'panier' avec la valeur de totalQuantité actuelle + nbArticles
        $session->set('totalQuantite', $totalQuantite);

        // Mettre à jour le panier dans la session
        $session->set('panier', $panier);

        // Retourner une réponse JSON
        return new JsonResponse(['message' => 'produit ajouté au panier avec succès', 'success' => true, 'totalQuantite' => $totalQuantite]);
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
            };
        }
        $session->set('panier', $articles);
        $totalQuantite = array_sum(array_column($articles, 'nbArticles'));
        $session->set('totalQuantite', $totalQuantite);
        // $session->remove('panier');

        // dd($articles);
        return new JsonResponse(['success' => true]);
    }
}
