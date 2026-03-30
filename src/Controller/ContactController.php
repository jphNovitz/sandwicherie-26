<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Form\ContactType;
use App\Service\FrontDataProvider;
use App\Service\SiteMapBuilder;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, FrontDataProvider $frontDataProvider, SiteMapBuilder $siteMapBuilder): Response
    {
        $page = $frontDataProvider->getPage(PageCode::CONTACT);
        $siteSettings = $frontDataProvider->getSiteSettings();

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && '' !== trim((string) $form->get('website')->getData())) {
            return $this->redirectToRoute('app_contact');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $form->addError(new FormError('Merci de verifier les informations du formulaire avant de renvoyer votre message.'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre message a bien ete envoye.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
            'page' => $page,
            'site_settings' => $siteSettings,
            'contact_map' => $siteSettings ? $siteMapBuilder->buildContactMap($siteSettings) : null,
            'itinerary_url' => $siteSettings ? $siteMapBuilder->buildItineraryUrl($siteSettings) : null,
            'opening_hours' => $frontDataProvider->getOpeningHours(),
            'social_links' => $frontDataProvider->getSocialLinks(),
        ]);
    }
}
