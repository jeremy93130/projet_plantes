<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AdresseFactureRepository;
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
        $commandeData = $data['commandeData'];
        $totalGeneral = $data['totalGeneral'];

        $commandeArray = ['commandeData' => $commandeData, 'totalGeneral' => $totalGeneral];

        $session->set('commande', $commandeArray);


        // Vous pouvez renvoyer une réponse JSON en fonction de vos besoins
        return new JsonResponse(['redirect' => $this->generateUrl('recapp_commande')]);
    }

    #[Route('/recap', name: 'recapp_commande')]
    public function recap(SessionInterface $session, AdresseRepository $adresseRepository, AdresseFactureRepository $adresseFactureRepository): Response
    {
        $sessionCommande = $session->get('commande', []);

        $commandeData = $sessionCommande;
        $totalGeneral = 0;

        foreach ($commandeData['commandeData'] as $key => &$value) {

            if (!isset($value['prixTTC'])) {
                $value['prixTTC'] = $value['prix'];
                $prixTTC = ($value['categorie'] == 1) ? 0.1 : 0.055;
                $value['prixTTC'] += $value['prix'] * $prixTTC;
            }

            $sessionCommande['commandeData'][$key]['prixTTC'] = floatval($value['prixTTC']);

            $prixTTC = $value['prixTTC'];

            $sessionCommande['commandeData'][$key]['prixTTC'] = round($prixTTC, 2);

            if ($totalGeneral < 50) {
                $totalGeneral += $prixTTC * $value['quantite'] + 3.99;
            } else {
                $totalGeneral += $prixTTC * $value['quantite'];
            }

        }

        $session->set('commande', $commandeData);
        $sessionCommande['totalGeneral'] = round($totalGeneral, 2);
        // dd($sessionCommande);

        $linksParameters = [];
        if (!$this->getUser()) {
            $linksParameters = ['errorPanier' => 'Veuillez vous connecter'];
            $url = $this->generateUrl('app_login', $linksParameters);
            return $this->redirect($url);
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $userId = $user->getId();

        $commande = $session->get('adresseData');
        $commandeFacture = $session->get('adresseDataFacture') ?? $commande;

        if (empty($commandeFacture)) {
            $session->set('adresseDataFacture', $commande);
        }

        $usedAdresse = $adresseRepository->findByLast($userId);
        $usedFactureAdresse = $adresseFactureRepository->findByLast($userId);

        return $this->render('commandes/commandes.html.twig', [
            'adresseInfos' => $commande,
            'adresseFactureInfos' => $commandeFacture,
            'userLastAdresse' => $usedAdresse,
            'userLastFactureAdresse' => $usedFactureAdresse,
            'dataCommande' => $sessionCommande,
            'user' => $user,
            'successMessage' => null
        ]);
    }



    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirm()
    {

        return $this->render('commandes/commandes.html.twig', ["successMessage" => true]);
    }
}
