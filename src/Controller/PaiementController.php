<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Entity\DetailsCommande;
use App\Repository\AdresseRepository;
use App\Repository\ProduitsRepository;
use App\Service\AdresseService;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{

    #[Route('/paiement', name: 'app_paiements')]
    public function index(SessionInterface $session): Response
    {
        $sessionCommande = $session->get('commande');
        $user = $this->getUser();

        // Récupérer les informations nécessaires de la session ou ailleurs
        $successMessage = $session->has('success_url') ? $session->get('success_url') : null;

        return $this->render('commandes/commandes.html.twig', [
            'dataCommande' => $sessionCommande,
            'userInfo' => $user,
            'successMessage' => $successMessage,
        ]);
    }

    #[Route('/order/create-session-stripe/{ids}/{total}', name: 'app_paiement')]
    public function stripeCheckout(SessionInterface $sessionInterface,UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        \Stripe\Stripe::setApiKey('sk_test_51OICEgC3GA5BR02Af7eTScs2GgI29d4FpjzMiWRo625SCPzvudJNRQPg0A3ICZ9wTnCiXJadx9TrO7MRr9lVaXV800sjafT7mP'); // Remplacez par votre clé secrète Stripe
        if (!isset ($sessionInterface->get('commande')['totalGeneral']) || $sessionInterface->get('commande')['totalGeneral'] == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $commandeData = $sessionInterface->get('commande')['commandeData'];
            // dd($commandeData);
            $totalData = $sessionInterface->get('commande')['totalGeneral'];
            $lineItems = [];

            // Ajouter des frais de livraison si le total est inférieur à 50
            if ($totalData < 50) {
                $unitAmount = round(3.99 * 100);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Frais de livraison',
                        ],
                        'unit_amount' => $unitAmount, // Le prix doit être en centimes
                    ],
                    'quantity' => 1, // Vous pouvez ajuster la quantité si nécessaire
                ];
            }
            foreach ($commandeData as $item) {
                $unitAmount = round($item['prixTTC'] * 100);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item['alt'],
                        ],
                        'unit_amount' => $unitAmount, // Le prix doit être en centimes
                    ],
                    'quantity' => $item['quantite'],
                ];
            }
        }
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('handle_successful_payment', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('cancel_payment', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new RedirectResponse($checkout_session->url, 303);
    }

    #[Route('/handle-successful-payment', name: 'handle_successful_payment')]
    public function handleSuccessfulPayment(SessionInterface $session, EntityManagerInterface $entityManager, ProduitsRepository $produitRepository, AdresseService $adresseService, CommandeService $commandeService, AdresseRepository $adresseRepository): RedirectResponse
    {

        $session->set('adresseValide', false);
        $session->set('adresseFactureValide', false);

        // Récupérer l'identifiant de l'utilisateur depuis votre tableau de données
        /** @var User */
        $user = $this->getUser();

        // Récupérer les informations de la session
        $adresseInfo = $session->get('adresseData') ?? $adresseRepository->findByLastLivraison($user);

        $adresseFactureInfo = $session->get('adresseDataFacture') ?? $adresseRepository->findByLastFacture($user) ?? $adresseInfo;

        if (!empty ($adresseFactureInfo) && is_array($adresseFactureInfo)) {
            $adresseFactureInfo['type'] = "facturation";
            unset($adresseFactureInfo['instructions']);
        }
        ;
        //Récuperer les plantes dans la session panier 
        $panier = $session->get('commande', []);

        //Créer une nouvelle entité Commandes
        $commande = $commandeService->createNewCommande($this->getUser());

        $total = 0;
        $quantiteTotale = 0;
        foreach ($panier['commandeData'] as $item) {
            $produit = $produitRepository->findOneById((int) $item['id']);
            if ($produit) {
                $quantite = $item['quantite'];
                $prix = $item['prixTTC'];
                $total += $quantite * $prix;
                // Accumuler la quantité totale
                $quantiteTotale += $quantite;

                // Vérifiez si le stock est suffisant
                if ($produit->getStock() >= $quantite) {
                    // Soustrayez la quantité du stock
                    $produit->setStock($produit->getStock() - $quantite);
                    // Créez une nouvelle entité Quantites
                    $detailsCommande = new DetailsCommande();
                    $detailsCommande->setQuantite($quantite);

                    // Associez la quantité à la plante et à la commande
                    $detailsCommande->setProduit($produit);
                    $detailsCommande->setCommande($commande);
                    $entityManager->persist($detailsCommande);
                }
            }
        }
        $commande->setTotal((float) number_format($total, 2));
        $entityManager->persist($commande);

        if (is_array($adresseInfo)) {
            // On enregistre l'adresse de livraison et de facturation
            $adresseService->createAndPersistAdresse(
                $adresseInfo,
                $user,
                $commande
            );
            $adresseService->createAndPersistAdresse(
                $adresseFactureInfo,
                $user,
                $commande
            );
        } else if ($adresseInfo instanceof Adresse && $adresseFactureInfo instanceof Adresse) {
            $adresseService->persistExistingAdresse($adresseInfo, $user, $commande);

            $adresseService->persistExistingAdresse($adresseFactureInfo, $user, $commande);
        }

        $adresseService->sauvegarderAdresse();

        // Supprimer les éléments de la session panier
        $session->remove('panier');

        // Marquer la variable de session pour indiquer que la redirection a eu lieu
        $session->set('redirected', true);

        // On remet le compteur des articles à 0
        $session->set('totalQuantite', 0);

        $session->remove('adresseData');
        $session->remove('adresseDataFacture');


        $response = new RedirectResponse($this->generateUrl('app_confirmation'));

        // Ajout d'un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Votre paiement a bien été accepté, merci pour votre commande');

        return $response;
    }

    #[Route('/cancel-payment', name: 'cancel_payment')]
    public function cancelPayment(SessionInterface $session): Response
    {
        $session->remove('commande');

        return $this->redirectToRoute('app_home');
    }
}
