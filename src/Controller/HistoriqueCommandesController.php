<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DetailsCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueCommandesController extends AbstractController
{
    #[Route('/historique/commandes', name: 'app_historique_commandes')]
    public function index(DetailsCommandeRepository $detailsCommande): Response
    {
        $user = $this->getUser();

        /**
         * @var User $user
         */
        $userId = $user->getId();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Si vous avez besoin d'accéder à toutes les commandes de l'utilisateur, utilisez $user->getCommandes() au lieu de $detailsCommande
        $commandes = $detailsCommande->findByUserId($userId);
        // dd($commandes);
        $formattedResults = [];
        foreach ($commandes as $detailsCommande) {
            $commandeObj = $detailsCommande->getCommande();
            $plante = $detailsCommande->getProduit();

            $commandeId = $commandeObj->getId();
            // Si la commande n'existe pas encore dans $formattedResults, crée une nouvelle entrée
            if (!isset ($formattedResults[$commandeId])) {
                // Si la commande n'existe pas encore dans $formattedResults, crée une nouvelle entrée
                if (!isset ($formattedResults[$commandeId])) {
                    $adresseLivraison = null;
                    $adresseFacturation = null;

                    // Parcourir les adresses associées à la commande
                    foreach ($commandeObj->getAdresses() as $adresse) {
                        if ($adresse->getType() === 'livraison') {
                            $adresseLivraison = [
                                'nom_complet' => $adresse->getNomComplet(),
                                'adresse' => $adresse->getAdresse(),
                                'code_postal' => $adresse->getCodePostal(),
                                'ville' => $adresse->getVille(),
                                'pays' => $adresse->getPays(),
                                'telephone' => $adresse->getTelephone(),
                                'instruction_livraison' => $adresse->getInstructionLivraison()
                                // Ajoutez d'autres propriétés d'adresse si nécessaire
                            ];
                        } elseif ($adresse->getType() === 'facturation') {
                            $adresseFacturation = [
                                'nom_complet' => $adresse->getNomComplet(),
                                'adresse' => $adresse->getAdresse(),
                                'code_postal' => $adresse->getCodePostal(),
                                'ville' => $adresse->getVille(),
                                'pays' => $adresse->getPays(),
                                'telephone' => $adresse->getTelephone(),
                                // Ajoutez d'autres propriétés d'adresse si nécessaire
                            ];
                        }

                        // Sortir de la boucle dès que les deux adresses sont trouvées
                        if ($adresseLivraison !== null && $adresseFacturation !== null) {
                            break;
                        }
                    }

                    $formattedResults[$commandeId] = [
                        'commande' => $commandeObj,
                        'date_commande' => $commandeObj->getDateCommande(),
                        'nom_client' => $detailsCommande,
                        'adresse_livraison' => $adresseLivraison,
                        'adresse_facturation' => $adresseFacturation,
                        'produits' => [],
                        'total' => $commandeObj->getTotal(),
                    ];
                }
            }

            // Ajoute le produit à la commande existante
            $formattedResults[$commandeId]['produits'][] = [
                'produit' => $plante->getNomProduit(),
                'prix' => $plante->getPrixProduit(),
                'quantite' => $detailsCommande->getQuantite(),
            ];
        }

        // dd($formattedResults);

        return $this->render('historique_commandes/historique.html.twig', [
            'commandes' => $formattedResults,
            'user' => $user,
        ]);
    }
}
