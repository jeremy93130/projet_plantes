<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InfoUtilisateurController extends AbstractController
{
    #[Route('/info/utilisateur', name: 'app_info_utilisateur')]
    public function index(): Response
    {
        return $this->render('info_utilisateur/infosUtilisateur.html.twig', [
        ]);
    }

    #[Route('/update', name: 'update_user_infos')]
    public function updateUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManagerInterface)
    {
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $telephone = $request->request->get('telephone');
        $mdp = $request->request->get('mdp');


        // Vérifiez si les valeurs ne sont pas nulles avant de les utiliser
        $nom = $nom !== null ? $nom : '';
        $prenom = $prenom !== null ? $prenom : '';
        $email = $email !== null ? $email : '';
        $telephone = $telephone !== null ? intval($telephone) : 0;
        $mdp = $mdp !== null ? $mdp : '';

        /**
         * @var User $user
         */
        $user = $this->getUser();


        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);

        //Hasher le mdp :
        $hashedPassword = $passwordHasher->hashPassword($user, $mdp);
        $user->setMotDePasse($hashedPassword);
        $user->setTelephone($telephone);

        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        return new JsonResponse(["redirect" => $this->generateUrl('app_home')]);
    }
}
