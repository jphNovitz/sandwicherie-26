<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
final class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator
            ->setController(SiteSettingsCrudController::class)
            ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sandwicherie 26');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Site', 'fa fa-globe');
        yield MenuItem::linkTo(SiteSettingsCrudController::class, 'Reglages du site', 'fa fa-sliders');
        yield MenuItem::linkTo(PageCrudController::class, 'Pages', 'fa fa-file-lines');
        yield MenuItem::linkTo(SocialLinkCrudController::class, 'Reseaux sociaux', 'fa fa-share-nodes');
        yield MenuItem::linkTo(OpeningHourCrudController::class, 'Horaires', 'fa fa-clock');

        yield MenuItem::section('Carte', 'fa fa-utensils');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Categories', 'fa fa-layer-group');
        yield MenuItem::linkTo(ProductCrudController::class, 'Produits', 'fa fa-box');
        yield MenuItem::linkTo(AllergenCrudController::class, 'Allergenes', 'fa fa-triangle-exclamation');

        yield MenuItem::section('Contenus', 'fa fa-pen-to-square');
        yield MenuItem::linkTo(GalleryImageCrudController::class, 'Galerie', 'fa fa-images');
        yield MenuItem::linkTo(ReviewCrudController::class, 'Avis clients', 'fa fa-star');
        yield MenuItem::linkTo(FeaturedItemCrudController::class, 'Mise en avant accueil', 'fa fa-bullhorn');
        yield MenuItem::linkTo(AboutHighlightCrudController::class, 'Points forts A propos', 'fa fa-circle-check');

        yield MenuItem::section('Contacts', 'fa fa-envelope');
        yield MenuItem::linkTo(ContactMessageCrudController::class, 'Messages', 'fa fa-inbox');
    }
}
