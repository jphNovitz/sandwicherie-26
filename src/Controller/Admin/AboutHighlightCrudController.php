<?php

namespace App\Controller\Admin;

use App\Entity\AboutHighlight;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class AboutHighlightCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AboutHighlight::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Point fort')
            ->setEntityLabelInPlural('Points forts')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield TextField::new('title', 'Titre');
        yield TextareaField::new('shortText', 'Texte court')->hideOnIndex();
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Actif');
        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();
    }
}
