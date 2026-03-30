<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Page')
            ->setEntityLabelInPlural('Pages')
            ->setDefaultSort(['code' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Page');
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield ChoiceField::new('code', 'Code');
        yield TextField::new('title', 'Titre');
        yield SlugField::new('slug', 'Slug')->setTargetFieldName('title');
        yield BooleanField::new('isActive', 'Active');

        yield FormField::addFieldset('Contenus');
        yield TextareaField::new('introduction', 'Introduction')->hideOnIndex();
        yield TextEditorField::new('content', 'Contenu')->hideOnIndex();

        yield FormField::addFieldset('SEO');
        yield TextField::new('seoTitle', 'SEO title')->hideOnIndex();
        yield TextareaField::new('metaDescription', 'Meta description')->hideOnIndex();

        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm();
    }
}
