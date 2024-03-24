<?php

namespace App\Service;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    public static function isInCart(SessionInterface $sessionInterface, $productId)
    {
        $sessionPanier = $sessionInterface->get('panier', []);
        foreach ($sessionPanier as $session) {
            if ($session['id'] == $productId) {
                return true;
            }
        }
        return false;
    }
}
