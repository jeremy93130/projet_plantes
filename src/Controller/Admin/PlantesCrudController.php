<?php

namespace App\Controller\Admin;

use App\Entity\Plantes;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlantesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plantes::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom_plante', 'Nom de la plante'),
            TextareaField::new('description_plante','description de la plante')->renderAsHtml(),
            NumberField::new('prix_plante', 'Prix de la plante'),
            NumberField::new('stock', 'En Stock'),
            ImageField::new('image')
                ->setBasePath('/images/plantes')
                ->setUploadDir('public/images/plantes')
                ->setRequired(false),
        ];
    }
}
