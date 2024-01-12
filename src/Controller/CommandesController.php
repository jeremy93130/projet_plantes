<?php

namespace App\Controller;

use App\Entity\User;
use Faker\Core\Number;
use App\Entity\Adresse;
use App\Form\AdresseLivraisonType;
use App\Repository\AdresseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(SessionInterface $session, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $session->set('commande', $data);
        // Faites quelque chose avec $data (prix total, prix et quantités des plantes)

        // Vous pouvez renvoyer une réponse JSON en fonction de vos besoins
        return new JsonResponse(['message' => 'Commande reçue avec succès!', 'redirect' => $this->generateUrl('recapp_commande'), 'data' => $data]);
    }

    #[Route('/recap', name: 'recapp_commande')]
    public function recap(SessionInterface $session, AdresseRepository $adresseRepository): Response
    {
        $sessionCommande = $session->get('commande');
        // dd($sessionCommande);
        foreach ($sessionCommande['commandeData'] as $key => $value) {
            $sessionCommande['commandeData'][$key]['tva'] = floatval(round($value['tva'], 3));
            $sessionCommande['commandeData'][$key]['prixTTC'] = floatval($value['prixTTC']);

            $prixTTC = $value['prix'] + $value['tva'];

            $sessionCommande['commandeData'][$key]['prixTTC'] = round($prixTTC, 2);
        }

        $totalTVA = 0;

        foreach ($sessionCommande['commandeData'] as $tva) {
            $totalTVA += $tva['prixTTC'] * $tva['quantite'];
        }
        if ($sessionCommande['totalGeneral'] < 50) {
            $totalTVA += 3.99;
        }

        $sessionCommande['totalGeneral'] = round($totalTVA, 2);

        // dd($sessionCommande['commandeData']);
        $linksParameters = [];
        if (!$this->getUser()) {
            $linksParameters = ['errorPanier' => 'Veuillez vous connecter'];
            $url = $this->generateUrl('app_login', $linksParameters);
            return $this->redirect($url);
        }

        // dd($tva);
        // dd($sessionCommande);
        // dd($sessionPanier);
        // dd($successMessage);
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $userId = $user->getId();

        $commande = $session->get('adresseData');



        $usedAdresse = $adresseRepository->findByLast($userId);

        // dd($sessionCommande);
        return $this->render('commandes/commandes.html.twig', [
            'adresseInfos' => $commande,
            'userLastAdresse' => $usedAdresse,
            'dataCommande' => $sessionCommande,
            'user' => $user,
            'successMessage' => null
        ]);
    }

    #[Route('/adresse', name: 'app_adresse')]
    public function adresse(Request $request, SessionInterface $session): Response
    {

        $adresse = new Adresse();
        $user = $this->getUser();

        $form = $this->createForm(AdresseLivraisonType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nomLivraison = $form->get('Nom_complet')->getData();
            $adresseLivraison = $form->get('adresse')->getData();
            $codePostal = $form->get('code_postal')->getData();
            $ville = $form->get('ville')->getData();
            $pays = $form->get('pays')->getData();
            $instructions = $form->get('instructionLivraison')->getData();

            $adresse->setNomComplet($nomLivraison);
            $adresse->setAdresse($adresseLivraison);
            $adresse->setCodePostal($codePostal);
            $adresse->setVille($ville);
            $adresse->setPays($pays);
            $adresse->setClient($user);
            if ($instructions == null) {
                $adresse->setInstructionLivraison("aucune instruction");
            } else {
                $adresse->setInstructionLivraison($instructions);
            }

            //Stocker les données dans la session : 
            $session->set('adresseData', [
                'nomComplet' => $nomLivraison,
                'adresseLivraison' => $adresseLivraison,
                'codePostal' => $codePostal,
                'ville' => $ville,
                'pays' => $pays,
                'instructions' => $instructions,
            ]);

            // Set la session à true adresse pour afficher l'adresse
            $session->set('adresseValide', true);

            return $this->redirectToRoute('recapp_commande');
        }


        return $this->render('adresse/adresse.html.twig', [
            'formAdresse' => $form->createView(),
        ]);
    }

    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirm()
    {

        return $this->render('commandes/commandes.html.twig', ["successMessage" => true]);
    }
}
