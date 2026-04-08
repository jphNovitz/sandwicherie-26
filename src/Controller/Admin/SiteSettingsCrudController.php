<?php

namespace App\Controller\Admin;

use App\Admin\MediaGuidelines;
use App\Entity\SiteSettings;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class SiteSettingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteSettings::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reglage du site')
            ->setEntityLabelInPlural('Reglages du site')
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->disable(Action::BATCH_DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Identite');
        yield TextField::new('businessName', 'Nom de l\'enseigne')
            ->setHelp('En pratique, une seule fiche est attendue pour le site.');
        yield TextField::new('slogan', 'Accroche globale')->hideOnIndex();

        yield FormField::addFieldset('Coordonnees');
        yield TextField::new('phone', 'Telephone')->hideOnIndex();
        yield TextField::new('email', 'Email')->hideOnIndex();
        yield TextField::new('whatsapp', 'WhatsApp')->hideOnIndex();
        yield TextareaField::new('fullAddress', 'Adresse complete')->hideOnIndex();
        yield TextField::new('shortAddress', 'Adresse courte');

        yield FormField::addFieldset('Localisation');
        yield NumberField::new('latitude', 'Latitude')
            ->setNumDecimals(7)
            ->hideOnIndex();
        yield NumberField::new('longitude', 'Longitude')
            ->setNumDecimals(7)
            ->hideOnIndex();

        yield FormField::addFieldset('Formulaire et notifications');
        yield TextField::new('notificationEmail', 'Email de notification')->hideOnIndex();
        yield BooleanField::new('emailNotificationsEnabled', 'Notifications email');

        yield FormField::addFieldset('Media global');
        yield ImageField::new('logo', 'Logo')
            ->setBasePath('images/siteConfig')
            ->setUploadDir('public/images/siteConfig')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setFileConstraints(MediaGuidelines::logoConstraints())
            ->setHelp(MediaGuidelines::logoHelp())
            ->hideOnIndex();
        yield ImageField::new('heroImage', 'Image hero')
            ->setBasePath('images/siteConfig')
            ->setUploadDir('public/images/siteConfig')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setFileConstraints(MediaGuidelines::heroConstraints())
            ->setHelp(MediaGuidelines::heroHelp())
            ->hideOnIndex();

        yield FormField::addFieldset('Technique');
        yield TextareaField::new('generalNotes', 'Notes generales')->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Cree le')->hideOnForm()->hideOnIndex();
        yield DateTimeField::new('updatedAt', 'Mis a jour le')->hideOnForm()->hideOnIndex();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        $this->addFlash('warning', 'Les reglages du site ne peuvent pas etre supprimes depuis l’administration.');
    }
}
