<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitSearchType;
use App\Repository\ProduitsRepository;
use App\Service\PanierService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatsController extends AbstractController
{
    #[Route('/achats/{categorie}', name: 'app_achats')]
    public function index(ProduitsRepository $produitRepository, Request $request, $categorie, SessionInterface $session): Response
    {
        $form = $this->createForm(ProduitSearchType::class);
        $form->handleRequest($request);
        $produit = $produitRepository->findByCategory($categorie);
        // dd($produit);

        $cssClass = 'achat-accueil';

        // On met une classe spécifique aux catégories d'article, celà changera le background en fonction de la catégorie
        // De plus on ajoutera un text spécifique aux catégories également
        switch ($categorie) {
            case 1:
                $cssClass .= '-plantes';
                break;
            case 2:
                $cssClass .= '-graines';
                break;
            case 3:
                $cssClass .= '-legumes';
                break;
            case 4:
                $cssClass .= '-fruits';
                break;
            default:
                $cssClass .= '-defaut';
        }

        $data = $produit;

        // Créer un adaptateur PagerFanta avec les données

        $adapter = new ArrayAdapter($data);

        // Créer une instance de pagerFanta 

        $pagerFanta = new Pagerfanta($adapter);

        // Définir le nombre d'élément par pages :

        $pagerFanta->setMaxPerPage(9);

        // récuperer le numéro de page à partir de la requete 

        $currentPage = $request->query->getInt('page', 1);

        // Définir la page actuelle

        $pagerFanta->setCurrentPage($currentPage);


        $data = $produitRepository->findAll();
        $cssPanier = array();
        foreach ($data as $key => $product) {
            if (PanierService::isInCart($session, $product->getId())) {
                $cssPanier[$product->getId()] = 'selected_cart'; // Définir la classe CSS pour ce produit
            } else {
                $cssPanier[$product->getId()] = ''; // No CSS class for this product
            }
        }

        return $this->render('achats/produits.html.twig', [
            'controller_name' => 'AchatsController',
            'form' => $form->createView(),
            'css' => $cssClass,
            'cssPanier' => $cssPanier,
            'pagination' => $pagerFanta,
        ]);
    }
}
