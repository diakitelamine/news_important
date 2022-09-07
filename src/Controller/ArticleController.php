<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Commentary;
use App\Form\ArticleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class ArticleController extends AbstractController
{

    /**
     * @Route("/admin/create/article", name="create_article", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function createArticle(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        # Nouvelle instance de la classe article (entity)
        $article = new Article();

        # Matérialisation du formulaire déclaré dans ArticleType.php
        $form = $this->createForm(ArticleType::class, $article);

        # handleRequest() sert à récupérer les données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($this->getUser());

            # Récupération des données du formulaire
            $article = $form->getData();

            # Définition de l'alias grâce à slugger, basé sur le titre. Slugger supprime les espaces et les caractères indésirables.
            $article->setAlias($slugger->slug($article->getTitle()));

            # Récupération du fichier du formulaire
            $file = $form->get('photo')->getData();

            if ($file) {

                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $extension = '.' . $file->guessExtension();

                $safeFilename = $slugger->slug($originalFileName);

                $newFilename = $safeFilename . '-' . uniqid() . $extension;

                try {
                    $file->move('uploads_dir', $newFilename);
                    $article->setPhoto($newFilename);
                } catch (FileException $exception) {
                    dd($exception);
                }
            }

            # Création du conteneur et insertion en base de données grâce à Doctrine et l'outil entityManager.
            $entityManager->persist($article);

            # On vide l'entity manager des données précédement contenues.
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez créé un nouvel article !');

            # Redirection sur la page d'accueil
            return $this->redirectToRoute('default_home');
        }

        return $this->render('article/form_article.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/update/article/{id}", name="update_article", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function updateArticle(Article $article, SluggerInterface $slugger, EntityManagerInterface $entityManager, Request $request): Response
    {   
        $form = $this->createForm(ArticleType::class, $article, [
            'photo' => $this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $article->getPhoto(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Récupération des données du formulaire
            $article = $form->getData();

            # Définition de l'alias grâce à slugger, basé sur le titre. Slugger supprime les espaces et les caractères indésirables.
            $article->setAlias($slugger->slug($article->getTitle()));

            # Récupération du fichier du formulaire
            $file = $form->get('photo')->getData();

            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $extension = '.' . $file->guessExtension();

                $safeFilename = $slugger->slug($originalFileName);

                $newFilename = $safeFilename . '-' . uniqid() . $extension;

                try {
                    $file->move('uploads_dir', $newFilename);
                    $article->setPhoto($newFilename);
                } catch (FileException $exception) {
                    dd($exception);
                }
            }

            # Création du conteneur et insertion en base de données grâce à Doctrine et l'outil entityManager.
            $entityManager->persist($article);

            # On vide l'entity manager des données précédement contenues.
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien modifié l\'article !');

            # Redirection sur la page d'accueil
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('article/form_article.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/show/article/{cat_alias}/{art_alias}", name="show_article", methods={"GET"})
     * @ParamConverter("article", options={"mapping": {"art_alias" : "alias"}})
     * @param Article $article
     * @return Response
     */
    public function showArticle(Article $article, EntityManagerInterface $entityManager): Response
    {
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy([ 'article' => $article->getId() ]);

        return $this->render('article/show_article.html.twig', [
            'article' => $article,
            'commentaries' => $commentaries,
        ]);
    }

    /**
     * @Route("/show/{alias}/articles/", name="show_article_from_category", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function showArticleFromCategory(Category $category, EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy([ 'category' => $category->getId() ]);

        return $this->render('article/show_articles_from_category.html.twig', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/delete/article/{id}", name="soft_delete_article", methods={"GET"})
     * @param Article $article
     * @return Response
     */
    public function softDeleteArticle(Article $article, EntityManagerInterface $entityManager): Response
    {
        $article->setDeletedAt(new DateTime());

        $entityManager->persist($article);

        $entityManager->flush();

        $this->addFlash('success', 'Vous avez bien archivé l\'article !');

        return $this->redirectToRoute('show_dashboard');
    }

}
