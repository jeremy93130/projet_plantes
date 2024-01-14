<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InfoUtilisateurController extends AbstractController
{
    #[Route('/info/utilisateur', name: 'app_info_utilisateur')]
    public function index(): Response
    {
        return $this->render('info_utilisateur/infosUtilisateur.html.twig', []);
    }

    #[Route('/update', name: 'update_user_infos')]
    public function updateUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManagerInterface)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $telephone = $request->request->get('telephone');
        $ancienMdp = $request->request->get('ancienMdp');
        $nouveauMdp = $request->request->get('nouveauMdp');
        $champModifie = $request->request->get('champModifie');

        switch ($champModifie) {
            case 'nom':
                $message = "Votre nom a bien été modifié";
                break;
            case 'prenom':
                $message = "Votre prenom a bien été modifié";
                break;
            case 'email':
                $message = "Votre email a bien été modifié";
                break;
            case 'telephone':
                $message = "Votre numéro de téléphone a bien été modifié";
                break;
            default:
                "Rien n'a été modifié";
        }

        // Vérifiez si les valeurs ne sont pas nulles avant de les utiliser
        $nom = $nom !== null ? $nom : $user->getNom();
        $prenom = $prenom !== null ? $prenom : $user->getPrenom();
        $email = $email !== null ? $email : $user->getEmail();
        $telephone = $telephone !== null ? ($telephone) : $user->getTelephone();
        $ancienMdp = $ancienMdp !== null ? trim($ancienMdp) : '';
        $nouveauMdp = $nouveauMdp !== null ? trim($nouveauMdp) : '';



        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setTelephone($telephone);

        $erreur_mdp = null;
        //Hasher le mdp :
        if (!empty($ancienMdp)) {
            // Vérifier l'ancien mot de passe
            if ($passwordHasher->isPasswordValid($user, $ancienMdp)) {
                // Le mot de passe actuel est correct, procédez à la mise à jour
                $hashedPassword = $passwordHasher->hashPassword($user, $nouveauMdp);
                $user->setMotDePasse($hashedPassword);
            } else {
                return new JsonResponse(["erreur_mdp" => "Mot de passe ancien incorrect"]);
            }
        }


        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        $success_message = "Vos informations ont bien été enregistrées";

        return new JsonResponse(["erreur_mdp" => $erreur_mdp ?? null, "success_message" => $success_message ?? null, "message" => $message ?? null]);
    }
}
