<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Plantes;
use App\Entity\Commandes;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse_livraison', TextType::class, [
                'label' => 'Entrez votre adresse de livraison',
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 5]),
                ],

            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('pays', CountryType::class, [
                'label' => 'Pays de Livraison',
                'constraints' => [

                ]
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
