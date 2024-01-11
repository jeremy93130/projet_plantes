<?php

namespace App\Controller;

use App\Form\ProduitSearchType;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatsController extends AbstractController
{
    #[Route('/achats/{categorie}', name: 'app_achats')]
    public function index(ProduitsRepository $produitRepository, Request $request, $categorie): Response
    {
        $form = $this->createForm(ProduitSearchType::class);
        $form->handleRequest($request);
        $produit = $produitRepository->findByCategory($categorie);
        // dd($produit);

        $cssClass = 'achat-accueil';

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


        return $this->render('achats/produits.html.twig', [
            'controller_name' => 'AchatsController',
            'form' => $form->createView(),
            "produits" => $produit,
            'css' => $cssClass
        ]);
    }
}
