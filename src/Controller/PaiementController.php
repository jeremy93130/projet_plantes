<?php

namespace App\Controller;

use App\Repository\PlantesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    #[Route('/order/create-session-stripe/{ids}/{total}', name: 'app_paiement')]
    public function stripeCheckout(EntityManagerInterface $entityManager, $ids, $total, PlantesRepository $plantesRepository): RedirectResponse
    {
        $idArray = explode(',', $ids);
        $order = $plantesRepository->findBy(['id' => $idArray]);
        $totalGeneral = $total;

        return $this->redirectToRoute('recapp_commande');
    }
}
