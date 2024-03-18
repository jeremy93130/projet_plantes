<?php


namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Form\AdresseLivraisonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseController extends AbstractController
{

    #[Route('/adresse', name: 'app_adresse')]
    public function adresse(Request $request, SessionInterface $session): Response
    {

        $adresse = new Adresse();
        $user = $this->getUser();

        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nomLivraison = $form->get('Nom_complet')->getData();
            $adresseLivraison = $form->get('adresse')->getData();
            $codePostal = $form->get('code_postal')->getData();
            $ville = $form->get('ville')->getData();
            $pays = $form->get('pays')->getData();
            $instructions = $form->get('instructionLivraison')->getData();
            $telephone = $form->get('telephone')->getData();

            $adresse->setNomComplet($nomLivraison);
            $adresse->setAdresse($adresseLivraison);
            $adresse->setCodePostal($codePostal);
            $adresse->setVille($ville);
            $adresse->setPays($pays);
            $adresse->setClient($user);
            $adresse->setTelephone($telephone);
            if ($instructions == null) {
                $adresse->setInstructionLivraison("aucune instruction");
            } else {
                $adresse->setInstructionLivraison($instructions);
            }
            $adresse->setType('livraison');

            //Stocker les données dans la session : 
            $session->set('adresseData', [
                'nomComplet' => $nomLivraison,
                'adresse' => $adresseLivraison,
                'codePostal' => $codePostal,
                'ville' => $ville,
                'pays' => $pays,
                'instructionLivraison' => $instructions,
                'telephone' => $telephone,
                'type' => 'livraison'
            ]);

            // Set la session à true adresse pour afficher l'adresse
            $session->set('adresseValide', true);

            return $this->redirectToRoute('recapp_commande');
        }

        return $this->render('adresse/adresse.html.twig', [
            'formAdresse' => $form->createView(),
        ]);
    }
    #[Route('/adresse_facture', name: 'app_adresse_facture')]
    public function adresseFacture(Request $request, SessionInterface $session): Response
    {

        $adresse = new Adresse();
        $user = $this->getUser();

        $form = $this->createForm(AdresseType::class, $adresse, ['type_page' => 'adresse_facture']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nomLivraison = $form->get('Nom_complet')->getData();
            $adresseLivraison = $form->get('adresse')->getData();
            $codePostal = $form->get('code_postal')->getData();
            $ville = $form->get('ville')->getData();
            $pays = $form->get('pays')->getData();
            $telephone = $form->get('telephone')->getData();

            $adresse->setNomComplet($nomLivraison);
            $adresse->setAdresse($adresseLivraison);
            $adresse->setCodePostal($codePostal);
            $adresse->setVille($ville);
            $adresse->setPays($pays);
            $adresse->setClient($user);
            $adresse->setTelephone($telephone);
            $adresse->setType('facture');

            //Stocker les données dans la session : 
            $session->set('adresseDataFacture', [
                'nomComplet' => $nomLivraison,
                'adresse' => $adresseLivraison,
                'codePostal' => $codePostal,
                'ville' => $ville,
                'pays' => $pays,
                'telephone' => $telephone,
                'type' => 'facture'
            ]);

            // Set la session à true adresse pour afficher l'adresse
            $session->set('adresseFactureValide', true);



            return $this->redirectToRoute('recapp_commande');
        }


        return $this->render('adresse/adresse_facture.html.twig', [
            'formAdresse' => $form->createView(),
        ]);
    }
}