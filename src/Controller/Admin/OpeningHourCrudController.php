<?php

namespace App\Controller\Admin;

use App\Entity\OpeningHour;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

final class OpeningHourCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OpeningHour::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Horaire')
            ->setEntityLabelInPlural('Horaires')
            ->setDefaultSort(['position' => 'ASC', 'dayOfWeek' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield FormField::addFieldset('Jour');
        yield AssociationField::new('siteSettings', 'Site')->autocomplete()->hideOnIndex();
        yield ChoiceField::new('dayOfWeek', 'Jour');
        yield BooleanField::new('isClosed', 'Ferme');
        yield IntegerField::new('position', 'Position');

        yield FormField::addFieldset('Premiere plage');
        yield TimeField::new('firstOpeningAt', 'Ouverture')->hideOnIndex();
        yield TimeField::new('firstClosingAt', 'Fermeture')->hideOnIndex();

        yield FormField::addFieldset('Deuxieme plage');
        yield TimeField::new('secondOpeningAt', 'Ouverture')->hideOnIndex();
        yield TimeField::new('secondClosingAt', 'Fermeture')->hideOnIndex();
    }
}
