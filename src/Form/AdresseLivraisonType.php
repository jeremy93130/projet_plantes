<?php

namespace App\Form;

use App\Entity\Adresse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;

class AdresseLivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_complet', TextType::class, [
                'label' => "Nom Complet",
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('adresse', TextType::class, [
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
                'constraints' => []
            ])
            ->add('telephone', TelType::class, [
                'attr' => [
                    'pattern' => '[0-9]{10}', // Ajoutez un motif si nécessaire
                ],
            ])
            ->add('instructionLivraison', TextareaType::class, [
                'label' => 'Ajouter des instructions de livraison (facultatif)',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'nombre maximum de caractère atteint !'
                    ])
                ]
            ])
            ->add('Ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
