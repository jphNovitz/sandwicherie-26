<?php

namespace App\Controller\Admin;

use App\Entity\GalleryImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class GalleryImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GalleryImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image de galerie')
            ->setEntityLabelInPlural('Galerie')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield TextField::new('image', 'Image');
        yield TextField::new('altText', 'Texte alternatif')->hideOnIndex();
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isActive', 'Active');
        yield DateTimeField::new('updatedAt', 'Mise a jour')->hideOnForm()->hideOnIndex();
    }
}
