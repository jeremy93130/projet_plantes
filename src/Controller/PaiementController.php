<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
// \Stripe\Stripe::setApiVersion("
// 2023-10-16");

class PaiementController extends AbstractController
{
    #[Route('/paiement/{total}', name: 'app_paiement')]
    public function index($total): Response
    {
        return $this->render('paiement/paiement.html.twig', []);
    }

}
