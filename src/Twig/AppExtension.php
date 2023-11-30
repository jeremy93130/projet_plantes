<?php

namespace App\Twig;

use App\Controller\PanierController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('custom_function', [$this, 'customFunction']),
        ];
    }

    public function customFunction(Request $request): string
    {
        $sessionArticles = $request->getSession()->get('panier');

        // Vérifiez si $sessionArticles n'est pas null avant d'appeler count
        $nbArticles = is_array($sessionArticles) ? count($sessionArticles) : 0;

        // Logique de la fonction personnalisée
        return (string)$nbArticles;
    }
}
