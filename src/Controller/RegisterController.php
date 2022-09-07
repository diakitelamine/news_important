<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        # Nouvelle instance de la classe user (entity)
        $user = new User();

        # Matérialisation du formulaire déclaré dans RegisterType.php
        $form = $this->createForm(RegisterType::class, $user);

        # handleRequest() sert à récupérer les données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Récupération des données du formulaire
            $user = $form->getData();

            # On set la valeur de createdAt
            // $user->setCreatedAt(new DateTime());

            # On set la valeur du role
            // $user->setRoles(['ROLE_USER']);

            # On set la valeur du password en hash pour qu'il ne soit pas affiché en clair dans la base de données
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            # Création du conteneur et insertion en base de données grâce à Doctrine et l'outil entityManager.
            $entityManager->persist($user);

            # On vide l'entity manager des données précédement contenues.
            $entityManager->flush();

            # Redirection sur la page de connexion après l'inscription
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
