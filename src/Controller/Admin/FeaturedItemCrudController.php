<?php

namespace App\Controller\Admin;

use App\Entity\FeaturedItem;
use Doctrine\ORM\EntityManagerInterface;
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
            ->setSearchFields(['customTitle', 'shortText', 'product.name', 'category.name'])
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('displayTarget', 'Cible')->onlyOnIndex();
        yield ChoiceField::new('type', 'Type')->onlyOnIndex();
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Actif');
        yield BooleanField::new('displayPrice', 'Afficher le prix');
        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();

        yield FormField::addFieldset('Cible');
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield ChoiceField::new('type', 'Type')
            ->setHelp('Choisis si la mise en avant pointe vers un produit ou une categorie.');
        yield AssociationField::new('product', 'Produit')
            ->autocomplete()
            ->hideOnIndex()
            ->setHelp('A renseigner uniquement si le type est "produit".');
        yield AssociationField::new('category', 'Categorie')
            ->autocomplete()
            ->hideOnIndex()
            ->setHelp('A renseigner uniquement si le type est "categorie".');

        yield FormField::addFieldset('Contenu');
        yield TextField::new('customTitle', 'Titre personnalise')->hideOnIndex();
        yield TextareaField::new('shortText', 'Texte personnalise')->hideOnIndex();
        yield BooleanField::new('displayPrice', 'Afficher le prix')
            ->hideOnIndex()
            ->setHelp('Ignore automatiquement cette option pour une categorie.');

        yield FormField::addFieldset('Affichage');
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Actif');
    }

    public function persistEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if (!$entityInstance instanceof FeaturedItem) {
            parent::persistEntity($entityManager, $entityInstance);

            return;
        }

        if (!$this->ensureActiveLimit($entityManager, $entityInstance)) {
            return;
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if (!$entityInstance instanceof FeaturedItem) {
            parent::updateEntity($entityManager, $entityInstance);

            return;
        }

        if (!$this->ensureActiveLimit($entityManager, $entityInstance)) {
            return;
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function ensureActiveLimit(EntityManagerInterface $entityManager, FeaturedItem $featuredItem): bool
    {
        if (!$featuredItem->isActive() || null === $featuredItem->getSiteSettings()) {
            return true;
        }

        $count = (int) $entityManager->createQueryBuilder()
            ->select('COUNT(fi.id)')
            ->from(FeaturedItem::class, 'fi')
            ->andWhere('fi.siteSettings = :siteSettings')
            ->andWhere('fi.isActive = true')
            ->setParameter('siteSettings', $featuredItem->getSiteSettings())
            ->getQuery()
            ->getSingleScalarResult();

        if (null !== $featuredItem->getId()) {
            --$count;
        }

        if ($count >= FeaturedItem::MAX_ACTIVE_ITEMS) {
            $this->addFlash('danger', 'Impossible d\'activer plus de 6 mises en avant pour un meme site.');

            return false;
        }

        return true;
    }
}
