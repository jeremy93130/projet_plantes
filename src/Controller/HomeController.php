<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\ImagesRepository;
use App\Repository\PlantesRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionInterface $session): Response
    {
        // return $this->redirectToRoute('app_achats');
        return $this->render('home/index.html.twig', []);
    }


    #[Route('/details/{id}/{categorie}', name: 'details')]
    public function show(ProduitsRepository $produitRepository, $id, SessionInterface $session, Request $request, EntityManagerInterface $entityManagerInterface, ImagesRepository $images, $categorie): Response
    {
        $produit = $produitRepository->find($id);

        $uploadImage = $request->files->get('imagePlante');
        $imageName = null;

        if ($request->isMethod('POST')) {
            if ($uploadImage != null) {
                $imageName = $uploadImage->getClientOriginalName();

                // Créer et enregistrer une nouvelle image
                $image = new Images();
                $image->setImageName($imageName);
                $image->setProduit($produit);

                $entityManagerInterface->persist($image);
                $entityManagerInterface->flush();
                // Déplacer l'image vers le répertoire public/images
                $uploadImage->move(
                    $this->getParameter('images_directory'), // Obtient le chemin du répertoire depuis les paramètres
                    $imageName
                );
                $previousPage = $session->get('previous_page', $this->generateUrl('details', ['id' => $id]));
                return $this->redirect($previousPage);
            }
        }

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


        $imageCarousel = $images->getImagesById($id);
        // dd($imageCarousel);


        return $this->render('details/details.html.twig', [
            "produit" => $produit,
            'errorPlante' => null,
            'carousel' => $imageCarousel,
            'css' => $cssClass,
        ]);
    }
}
