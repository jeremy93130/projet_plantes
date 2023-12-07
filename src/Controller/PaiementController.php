<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Plantes;
use App\Entity\DetailsCommandes;
use App\Form\AdresseLivraisonType;
use App\Repository\PlantesRepository;
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
        $commande = $session->get('adresseData');
        $sessionCommande = $session->get('commande');
        $user = $this->getUser();

        return $this->render('commandes/commandes.html.twig', [
            'adresseInfos' => $commande,
            "adresseValide" => true,
            'dataCommande' => $sessionCommande,
            'userInfo' => $user
        ]);
    }

    #[Route('/adresse', name: 'app_adresse')]
    public function adresse(Request $request, SessionInterface $session): Response
    {

        $adresse = new DetailsCommandes();

        $form = $this->createForm(AdresseLivraisonType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresseLivraison = $form->get('adresse_livraison')->getData();
            $codePostal = $form->get('code_postal')->getData();
            $ville = $form->get('ville')->getData();
            $pays = $form->get('pays')->getData();

            $adresse->setAdresseLivraison($adresseLivraison);
            $adresse->setCodePostal($codePostal);
            $adresse->setVille($ville);
            $adresse->setPays($pays);

            //Stocker les données dans la session : 
            $session->set('adresseData', [
                'adresseLivraison' => $adresseLivraison,
                'codePostal' => $codePostal,
                'ville' => $ville,
                'pays' => $pays,
            ]);

            return $this->redirectToRoute('app_paiements');
        }


        return $this->render('adresse/adresse.html.twig', [
            'formAdresse' => $form->createView(),
        ]);
    }

    #[Route('/order/create-session-stripe/{ids}/{total}', name: 'app_paiement')]
    public function stripeCheckout(SessionInterface $sessionInterface, $ids, $total, UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        \Stripe\Stripe::setApiKey('sk_test_51OICEgC3GA5BR02Af7eTScs2GgI29d4FpjzMiWRo625SCPzvudJNRQPg0A3ICZ9wTnCiXJadx9TrO7MRr9lVaXV800sjafT7mP'); // Remplacez par votre clé secrète Stripe

        $commandeData = $sessionInterface->get('commande')['commandeData'];
        $totalGeneral = $sessionInterface->get('commande')['totalGeneral'];

        $lineItems = [];
        foreach ($commandeData as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['alt'],
                    ],
                    'unit_amount' => $item['prix'] * 100, // Le prix doit être en centimes
                ],
                'quantity' => $item['quantite'],
            ];
        }

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('handle_successful_payment', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('recapp_commande', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new RedirectResponse($checkout_session->url, 303);
    }

    #[Route('/handle-successful-payment', name: 'handle_successful_payment')]
    public function handleSuccessfulPayment(SessionInterface $session, EntityManagerInterface $entityManager, PlantesRepository $plantesRepository): Response
    {

        // Récupérer les informations de la session
        $adresseInfo = $session->get('adresseData');
        // Récupérer l'identifiant de l'utilisateur depuis votre tableau de données
        $userId = $this->getUser()->getId();

        //Récuperer les plantes dans la session panier 
        $panier = $session->get('panier', []);
        // dd($panier);

        // Récupérer l'objet User correspondant depuis la base de données
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        //Créer une nouvelle entité Commandes
        $commande = new DetailsCommandes();
        $commande->setClient($user);
        $commande->setDateCommande(new \DateTime()); // ou utilisez une date appropriée
        $commande->setEtatCommande('En Attente'); // ou utilisez l'état par défaut souhaité
        $commande->setAdresseLivraison($adresseInfo['adresseLivraison']);
        $commande->setVille($adresseInfo['ville']);
        $commande->setCodePostal($adresseInfo['codePostal']);
        $commande->setPays($adresseInfo['pays']);
        foreach ($panier as $item) {
            $plante = $plantesRepository->findOneById($item['id']);
            if ($plante) {
                $quantite = $item['quantite'];
                $prix = $item['prix'];
                $total = $quantite * $prix;

                $commande->addPlante($plante);
            }
        }
        $commande->setTotal($total);
        try {
            $entityManager->persist($commande);
            $entityManager->flush();
        } catch (\Exception $e) {
            $e->getMessage();
        }
        // Supprimer les éléments du panier
        $session->remove('commande');
        $session->remove('panier');

        // Récupérer les informations nécessaires de la session ou ailleurs
        $successMessage = $session->get('success_url');

        // Fournir une réponse appropriée à l'utilisateur (redirection, affichage de confirmation, etc.)
        return $this->render('commandes/commandes.html.twig', ['successMessage' => $successMessage]);
    }
}

