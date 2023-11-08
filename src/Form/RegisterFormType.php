<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Nom Requit !']),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 500,
                        'minMessage' => 'Le nom doit comporter au moins UN caractère',
                        'maxMessage' => 'Le nom de 500 caractère est un petit peu trop long ;)',
                    ]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Prénom Requit !']),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 500,
                        'minMessage' => 'Le prénom doit comporter au moins UN caractère',
                        'maxMessage' => 'Le prénom de 500 caractère est un petit peu trop long ;)',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'email est requit"]),
                    new Assert\Email(["message" => "L'email n'est pas valide"]),
                ],
            ])
            ->add('mot_de_passe', PasswordType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Entrez votre mot de passe',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('telephone', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Numéro de téléphone requit !']),
                ],
            ])
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
