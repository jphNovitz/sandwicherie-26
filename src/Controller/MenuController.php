<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
    #[Route('/carte', name: 'app_menu', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'code' => PageCode::MENU,
            'isActive' => true,
        ]);

        $categories = $entityManager->getRepository(Category::class)->findBy([
            'isActive' => true,
        ], [
            'position' => 'ASC',
            'id' => 'ASC',
        ]);

        $menuCategories = [];

        foreach ($categories as $category) {
            $products = array_values(array_filter(
                $category->getProducts()->toArray(),
                static fn (Product $product) => $product->isAvailable()
            ));

            usort($products, static fn (Product $left, Product $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

            if ([] === $products) {
                continue;
            }

            $menuCategories[] = [
                'category' => $category,
                'products' => $products,
            ];
        }

        return $this->render('menu/index.html.twig', [
            'page' => $page,
            'menu_categories' => $menuCategories,
        ]);
    }
}
