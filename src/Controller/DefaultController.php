<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManager)
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);

        return $this->render('default/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/render-header-categories", name="render_header_categories", methods={"GET"})
     */
    public function renderHeaderCategories(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findBy(['deletedAt' => null]);

        return $this->render('rendered/nav_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/render-footer-categories", name="render_footer_categories", methods={"GET"})
     */
    public function renderFooterCategories(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findBy(['deletedAt' => null]);

        return $this->render('rendered/footer_categories.html.twig', [
            'categories' => $categories,
        ]);
    }   
}
