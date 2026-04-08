<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

final class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Avis client')
            ->setEntityLabelInPlural('Avis clients')
            ->setSearchFields(['firstName', 'lastInitial', 'content', 'sourceLabel'])
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield TextField::new('displayAuthor', 'Auteur')->onlyOnIndex();
        yield IntegerField::new('rating', 'Note / 5');
        yield TextField::new('displaySourceLabel', 'Source')->onlyOnIndex();
        yield TextField::new('content', 'Apercu')
            ->onlyOnIndex()
            ->formatValue(static fn (?string $value) => null === $value ? '' : mb_strimwidth($value, 0, 80, '...'));
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isVisible', 'Visible');
        yield DateTimeField::new('createdAt', 'Cree le')->hideOnForm();

        yield FormField::addFieldset('Auteur')->onlyOnForms();
        yield TextField::new('firstName', 'Prenom');
        yield TextField::new('lastInitial', 'Initiale')
            ->setHelp('Exemple : P');

        yield FormField::addFieldset('Avis')->onlyOnForms();
        yield TextEditorField::new('content', 'Texte de l\'avis')->hideOnIndex();
        yield IntegerField::new('rating', 'Note / 5')
            ->setHelp('Valeur entiere entre 1 et 5.');

        yield FormField::addFieldset('Source')->onlyOnForms();
        yield ChoiceField::new('sourceType', 'Type de source');
        yield TextField::new('sourceLabel', 'Libelle personnalise')
            ->setHelp('Optionnel. Si vide, le libelle par defaut du type sera affiche.');
        yield UrlField::new('sourceUrl', 'Lien')
            ->setHelp('Optionnel. Peut pointer vers l\'avis ou la plateforme source.');

        yield FormField::addFieldset('Affichage')->onlyOnForms();
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isVisible', 'Visible');
        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();
    }
}
