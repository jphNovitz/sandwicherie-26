<?php

namespace App\Controller\Admin;

use App\Entity\FeaturedItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class FeaturedItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeaturedItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Mise en avant')
            ->setEntityLabelInPlural('Mises en avant')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Configuration');
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield ChoiceField::new('type', 'Type');
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Active');
        yield BooleanField::new('showPrice', 'Afficher le prix');

        yield FormField::addFieldset('Contenu');
        yield TextField::new('customTitle', 'Titre personnalise')->hideOnIndex();
        yield TextareaField::new('shortText', 'Texte court')->hideOnIndex();
        yield AssociationField::new('product', 'Produit')->autocomplete()->hideOnIndex();
        yield AssociationField::new('category', 'Categorie')->autocomplete()->hideOnIndex();
        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();
    }
}
