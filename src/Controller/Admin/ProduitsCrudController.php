<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom_produit', 'Nom du produit'),
            TextareaField::new('description_produit', 'description du produit')->renderAsHtml(),
            TextareaField::new('caracteristiques', 'caractéristiques du produit')->renderAsHtml(),
            TextareaField::new('entretien', 'conseils d\'entretien')->renderAsHtml(true)->formatValue(function ($value) {
                return nl2br($value);
            }),
            NumberField::new('prix_produit', 'Prix du produit'),
            NumberField::new('stock', 'En Stock'),
            ImageField::new('image')->setBasePath('/images/produits')->setUploadDir('public/images/produits')->setRequired(false),
            NumberField::new('categorie'),
            NumberField::new('lot', 'Nombre de graines dans le lot')
        ];
    }
}
