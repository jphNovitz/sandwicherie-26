<?php

namespace App\Controller\Admin;

use App\Entity\SiteSettings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Identite');
        yield TextField::new('businessName', 'Nom de l’enseigne')
            ->setHelp('En pratique, une seule fiche est attendue pour le site.');
        yield TextField::new('slogan', 'Accroche')->hideOnIndex();

        yield FormField::addFieldset('Presentation');
        yield TextareaField::new('welcomeText', 'Texte d’accueil')->hideOnIndex();
        yield TextareaField::new('presentationText', 'Presentation')->hideOnIndex();

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

        yield FormField::addFieldset('Medias');
        yield TextField::new('logo', 'Logo')->hideOnIndex();
        yield TextField::new('heroImage', 'Image hero')->hideOnIndex();

        yield FormField::addFieldset('Technique');
        yield TextareaField::new('generalNotes', 'Notes generales')->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Cree le')->hideOnForm()->hideOnIndex();
        yield DateTimeField::new('updatedAt', 'Mis a jour le')->hideOnForm()->hideOnIndex();
    }
}
