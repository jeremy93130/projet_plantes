<?php

namespace App\Controller;

use App\Form\ProduitSearchType;
use App\Repository\ProduitsRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatsController extends AbstractController
{
    #[Route('/achats/{categorie}', name: 'app_achats')]
    // Injections de dépendances de symfony
    public function index(ProduitsRepository $produitRepository, Request $request, $categorie): Response
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

        $adapter = new ArrayAdapter($data);

        $pagerFanta = new Pagerfanta($adapter);

        $pagerFanta->setMaxPerPage(8);

        $currentPage = $request->query->getInt('page', 1);
        
        // Définir la page actuelle

        $pagerFanta->setCurrentPage($currentPage);


        return $this->render('achats/produits.html.twig', [
            'controller_name' => 'AchatsController',
            'form' => $form->createView(),
            'css' => $cssClass,
            'pagination' => $pagerFanta,
        ]);
    }
}
