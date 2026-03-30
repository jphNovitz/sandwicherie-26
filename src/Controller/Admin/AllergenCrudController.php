<?php

namespace App\Controller\Admin;

use App\Entity\Allergen;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class AllergenCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Allergen::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Allergene')
            ->setEntityLabelInPlural('Allergenes')
            ->setDefaultSort(['position' => 'ASC', 'name' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::BATCH_DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('code', 'Code');
        yield TextField::new('name', 'Libelle');
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Actif');
    }

    public function deleteEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if (!$entityInstance instanceof Allergen) {
            parent::deleteEntity($entityManager, $entityInstance);

            return;
        }

        if (!$entityInstance->getProducts()->isEmpty()) {
            $this->addFlash('danger', 'Impossible de supprimer cet allergene car il est encore lie a un ou plusieurs produits.');

            return;
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
}
