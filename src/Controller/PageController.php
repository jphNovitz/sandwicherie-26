<?php

namespace App\Controller;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/{slug}', name: 'app_page_show', priority: -100, methods: ['GET'])]
    public function show(string $slug, EntityManagerInterface $entityManager): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'slug' => $slug,
            'isActive' => true,
        ]);

        if (!$page instanceof Page) {
            throw $this->createNotFoundException();
        }

        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
