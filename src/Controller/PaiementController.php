<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{

    #[Route('/paiement', name: 'app_paiements')]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        $sessionCommande = $session->get('commande');
        dd($sessionCommande);
        $user = $this->getUser();

        // if ($commande == null || $sessionCommande == null || $user == null) {
        //     $this->redirectToRoute('app_home');
        // }

        // Récupérer les informations nécessaires de la session ou ailleurs
        $successMessage = $session->has('success_url') ? $session->get('success_url') : null;

        return $this->render('commandes/commandes.html.twig', [
            'dataCommande' => $sessionCommande,
            'userInfo' => $user,
            'successMessage' => null
        ]);
    }

    #[Route('/order/create-session-stripe/{ids}/{total}', name: 'app_paiement')]
    public function stripeCheckout(SessionInterface $sessionInterface, $ids, $total, UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        \Stripe\Stripe::setApiKey('sk_test_51OICEgC3GA5BR02Af7eTScs2GgI29d4FpjzMiWRo625SCPzvudJNRQPg0A3ICZ9wTnCiXJadx9TrO7MRr9lVaXV800sjafT7mP'); // Remplacez par votre clé secrète Stripe

        if (!isset($sessionInterface->get('commande')['totalGeneral']) || $sessionInterface->get('commande')['totalGeneral'] == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $commandeData = $sessionInterface->get('commande')['commandeData'];
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
    public function handleSuccessfulPayment(SessionInterface $session, EntityManagerInterface $entityManager, ProduitsRepository $produitRepository): Response
    {

        $session->set('adresseValide', false);
        // Récupérer les informations de la session
        $adresseInfo = $session->get('adresseData');

        // dd($adresseInfo);
        // Récupérer l'identifiant de l'utilisateur depuis votre tableau de données
        /** @var $userId */
        $user = $this->getUser();
        $userId = $user->getId();

        //Récuperer les plantes dans la session panier 
        $panier = $session->get('commande', []);

        // Récupérer l'objet User correspondant depuis la base de données
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        //Créer une nouvelle entité Commandes
        $commande = new Commande();
        $commande->setClient($user);
        $commande->setDateCommande(new \DateTimeImmutable()); // ou utilisez une date appropriée
        $commande->setEtatCommande('En Attente'); // ou utilisez l'état par défaut souhaité

        $total = 0;
        $quantiteTotale = 0;
        foreach ($panier['commandeData'] as $item) {
            $plante = $produitRepository->findOneById((int) $item['id']);
            if ($plante) {
                $quantite = $item['quantite'];
                $prix = $item['prixTTC'];
                $total += $quantite * $prix;
                // Accumuler la quantité totale
                $quantiteTotale += $quantite;

                // Vérifiez si le stock est suffisant
                if ($plante->getStock() >= $quantite) {
                    // Soustrayez la quantité du stock
                    $plante->setStock($plante->getStock() - $quantite);
                    // Créez une nouvelle entité Quantites
                    $detailsCommande = new DetailsCommande();
                    $detailsCommande->setQuantite($quantite);

                    // Associez la quantité à la plante et à la commande
                    $detailsCommande->setProduit($plante);
                    $detailsCommande->setCommande($commande);
                    $entityManager->persist($detailsCommande);
                }
            }
        }
        $adresse = new Adresse();
        $adresse->setNomComplet($adresseInfo['nomComplet']);
        $adresse->setAdresse($adresseInfo['adresseLivraison']);
        $adresse->setClient($user);
        $adresse->setCodePostal($adresseInfo['codePostal']);
        $adresse->setInstructionLivraison($adresseInfo['instructions']);
        $adresse->setPays($adresseInfo['pays']);
        $adresse->setVille($adresseInfo['ville']);
        $adresse->setCommande($commande);

        $entityManager->persist($adresse);
        $commande->setTotal((float) number_format($total, 2));
        // dd($total);

        $entityManager->persist($commande);
        $entityManager->flush();

        // Supprimer les éléments du panier
        // $session->remove('commande');
        $session->remove('panier');

        // Marquer la variable de session pour indiquer que la redirection a eu lieu
        $session->set('redirected', true);

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
