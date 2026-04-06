<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Categorie')
            ->setEntityLabelInPlural('Categories')
            ->setDefaultSort(['position' => 'ASC', 'name' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::BATCH_DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Categorie');
        yield TextField::new('name', 'Nom');
        yield SlugField::new('slug', 'Slug')
            ->setTargetFieldName('name')
            ->setHelp('Genere automatiquement a la creation, puis modifiable manuellement.');
        yield TextareaField::new('description', 'Description')->hideOnIndex();
        yield ImageField::new('image', 'Image')
            ->setBasePath('images/categories')
            ->setUploadDir('public/images/categories')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->hideOnIndex();
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Active');

        yield FormField::addFieldset('SEO');
        yield TextField::new('seoTitle', 'Titre SEO')->hideOnIndex();
        yield TextareaField::new('metaDescription', 'Meta description')->hideOnIndex();

        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if (!$entityInstance instanceof Category) {
            parent::deleteEntity($entityManager, $entityInstance);

            return;
        }

        if (!$entityInstance->getProducts()->isEmpty()) {
            $this->addFlash('danger', 'Impossible de supprimer cette categorie tant qu\'elle contient des produits.');

            return;
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
}
