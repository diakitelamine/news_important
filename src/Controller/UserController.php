<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/delete/user/{id}", name="soft_delete_user", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function softDeleteCategory(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setDeletedAt(new DateTime());

        $entityManager->persist($user);

        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien archivé l\' utilisateur !');

        return $this->redirectToRoute('show_dashboard');
    }
}