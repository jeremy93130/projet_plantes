<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\ImagesRepository;
use App\Repository\PlantesRepository;
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
    public function index(): Response
    {
        return $this->redirectToRoute('app_achats');
    }


    #[Route('/details/{id}', name: 'details')]
    public function show(PlantesRepository $plantesRepository, $id, SessionInterface $session, Request $request, EntityManagerInterface $entityManagerInterface, ImagesRepository $images): Response
    {
        $plantes = $plantesRepository->find($id);

        $uploadImage = $request->files->get('imagePlante');
        $imageName = null;

        if ($request->isMethod('POST')) {
            if ($uploadImage != null) {
                $imageName = $uploadImage->getClientOriginalName();

                // Créer et enregistrer une nouvelle image
                $image = new Images();
                $image->setImageName($imageName);
                $image->setPlante($plantes);

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


        $imageCarousel = $images->getImagesById($id);
        // dd($imageCarousel);

        return $this->render('details/details.html.twig', [
            "plante" => $plantes,
            'errorPlante' => null,
            'carousel' => $imageCarousel,
        ]);
    }
}
