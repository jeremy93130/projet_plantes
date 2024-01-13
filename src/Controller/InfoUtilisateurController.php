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

        // Vérifiez si les valeurs ne sont pas nulles avant de les utiliser
        $nom = $nom !== null ? $nom : '';
        $prenom = $prenom !== null ? $prenom : '';
        $email = $email !== null ? $email : '';
        $telephone = $telephone !== null ? intval($telephone) : 0;
        $ancienMdp = $ancienMdp !== null ? trim($ancienMdp) : $user->getMotDePasse();
        $nouveauMdp = $nouveauMdp !== null ? trim($nouveauMdp) : $user->getMotDePasse();



        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);

        $erreur_mdp = null;
        //Hasher le mdp :
        if (!empty($ancienMdp)) {
            // Vérifier l'ancien mot de passe
            if ($passwordHasher->isPasswordValid($user, $ancienMdp)) {
                // Le mot de passe actuel est correct, procédez à la mise à jour
                $hashedPassword = $passwordHasher->hashPassword($user, $nouveauMdp);
                $user->setMotDePasse($hashedPassword);
            } else {
                $erreur_mdp = "Mot de passe ancien ou nouveau incorrect";
            }

            $user->setTelephone($telephone);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return new JsonResponse(["erreur_mdp" => $erreur_mdp ?? null]);
        }
    }
}
