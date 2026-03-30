<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/carte/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function show(string $slug, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        /** @var Category|null $category */
        $category = $entityManager->getRepository(Category::class)->findOneBy([
            'slug' => $slug,
            'isActive' => true,
        ]);

        if (!$category instanceof Category) {
            throw $this->createNotFoundException();
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $productRepository->findBy([
                'category' => $category,
                'isAvailable' => true,
            ], ['position' => 'ASC', 'id' => 'ASC']),
        ]);
    }
}
