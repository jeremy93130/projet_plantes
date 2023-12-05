<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Form\RegisterFormType;
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

        $adresseLivraison = $request->get('adresse_livraison');
        $ville = $request->get('ville');
        $codePostal = $request->get('code_postal');
        $pays = $request->get('pays');

        $sessionCommande = $session->get('commande');
        $user = $this->getUser();

        return $this->render('commandes/commandes.html.twig', [
            'adresseLivraison' => $adresseLivraison,
            'ville' => $ville,
            'codePostal' => $codePostal,
            'pays' => $pays,
            "adresseValide" => true,
            'dataCommande' => $sessionCommande,
            'userInfo' => $user
        ]);
    }

    #[Route('/adresse', name: 'app_adresse')]
    public function adresse(Request $request, EntityManagerInterface $entityManager): Response
    {

        $adresse = new Commandes();

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

            $entityManager->persist($adresse);

            return $this->redirectToRoute('app_paiements', [
                'adresse_livraison' => $adresseLivraison,
                'ville' => $ville,
                'code_postal' => $codePostal,
                'pays' => $pays
            ]);
        }
        ;


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
            'success_url' => $urlGenerator->generate('recapp_commande', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('recapp_commande', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url, 303);
    }
}

