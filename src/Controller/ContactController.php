<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Entity\Page;
use App\Entity\SiteSettings;
use App\Form\ContactType;
use App\Service\SiteMapBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, SiteMapBuilder $siteMapBuilder): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'code' => PageCode::CONTACT,
            'isActive' => true,
        ]);
        /** @var SiteSettings|null $siteSettings */
        $siteSettings = $entityManager->getRepository(SiteSettings::class)->findOneBy([], ['id' => 'ASC']);

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

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
            'opening_hours' => $siteSettings ? $this->sortOpeningHours($siteSettings) : [],
            'social_links' => $siteSettings ? $this->sortSocialLinks($siteSettings) : [],
        ]);
    }

    private function sortOpeningHours(SiteSettings $siteSettings): array
    {
        $openingHours = $siteSettings->getOpeningHours()->toArray();

        usort($openingHours, static fn ($left, $right) => [$left->getPosition(), $left->getDayOfWeek()?->value] <=> [$right->getPosition(), $right->getDayOfWeek()?->value]);

        return $openingHours;
    }

    private function sortSocialLinks(SiteSettings $siteSettings): array
    {
        $socialLinks = array_filter(
            $siteSettings->getSocialLinks()->toArray(),
            static fn ($socialLink) => $socialLink->isActive()
        );

        usort($socialLinks, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return $socialLinks;
    }
}
