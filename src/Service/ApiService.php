<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ApiService
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public function fetchDataFromApi()
    {
        $response = $this->httpClient->request('GET', 'https://www.data.gouv.fr/fr/datasets/r/521fe6f9-0f7f-4684-bb3f-7d3d88c581bb');
        return $response->toArray(); // Ou utilisez d'autres méthodes selon le format de la réponse
    }
}