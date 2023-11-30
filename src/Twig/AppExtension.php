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
        $sessionArticles = $request->getSession()->get('nb_counts');
        $nbArticles = 0;
        if ($sessionArticles !== null && is_array($sessionArticles)) {
            $nbArticles = count($sessionArticles);
        }

        // Logique de la fonction personnalisée
        return (string) $nbArticles;
    }
}
