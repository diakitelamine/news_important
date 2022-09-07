<?php

namespace App\Controller;

use App\Entity\Category;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/delete/category/{id}", name="soft_delete_category", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function softDeleteCategory(Category $category, EntityManagerInterface $entityManager): Response
    {
        $category->setDeletedAt(new DateTime());

        $entityManager->persist($category);

        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien archivé la catégorie !');

        return $this->redirectToRoute('show_dashboard');
    }
}