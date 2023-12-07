<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Plantes;
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
    public function index(SessionInterface $session, PlantesRepository $plantesRepository): Response
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
        // Vérifier si l'ID de la plante est présent dans les données
        if (!isset($data['id'])) {
            return new JsonResponse(['message' => 'ID de plante manquant'], 400);
        }
        // Récupérer la plante depuis la base de données
        $plante = $entityManagerInterface->getRepository(Plantes::class)->find($id);

        // Vérifier si la plante existe
        if (!$plante) {
            return new JsonResponse(['message' => 'La plante n\'existe pas'], 404);
        }

        // Récupérer le panier actuel depuis la session
        $panier = $session->get('panier', []);

        // Vérifier si la plante est déjà dans le panier
        if (array_key_exists($id, $panier)) {
            // La plante est déjà dans le panier, vous pouvez ajuster votre réponse en conséquence
            return $this->render('details/details.html.twig', [
                'errorPlante' => 'La plante est déjà dans le panier'
            ]);
        }

        // Ajouter la plante au panier
        // Vous devez adapter cela en fonction de la structure réelle de vos données
        $panier[$id] = [
            'id' => $plante->getId(),
            'nom' => $plante->getNomPlante(),
            'prix' => $plante->getPrixPlante(),
            'image' => $plante->getImage(),
            'quantite' => 1, // Vous pouvez ajuster cela selon vos besoins
            'nbArticles' => $data["nbArticles"],
        ];

        // Mettre à jour le panier dans la session
        $session->set('panier', $panier);


        // Retourner une réponse JSON
        // return new JsonResponse(['message' => 'Plante ajoutée au panier avec succès', 'success' => true, 'data' => $dataToView]);
        return $this->redirectToRoute('app_achats');
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
        // $session->remove('panier');

        // dd($articles);
        return new JsonResponse(['success' => true]);
    }
}
