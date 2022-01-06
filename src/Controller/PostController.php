<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("posts", name="post_list")
     */                         // autowire
    public function postList(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll(); // findAll() récupère tous les posts de la bdd

        return $this->render('posts.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("post/{id}", name="post_show")
     */
    public function postShow($id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id); // find() permet de récupérer un poste grâce à son id.

        return $this->render('post.html.twig', ['post' => $post]);
    }

    /**
     * @Route("update/post/{id}", name="post_update")
     */
    public function postUpdate(
        $id,
        PostRepository $postRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {
        $post = $postRepository->find($id);

        // Création du formulaire
        $postForm = $this->createForm(PostType::class, $post);

        // Utilisation de handleRequest pour demander au formulaire de traiter les informations
        // rentrées dans le formulaire
        // Utilisation de request pour récupérer les informations rentrées dans le formualire
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {

            // persist prépare l'enregistrement dans la bdd
            // analyse le changement à faire 
            $entityManagerInterface->persist($post);
            // flush enregistre dans la bdd
            $entityManagerInterface->flush();

            return $this->redirectToRoute('post_list');
        }

        return $this->render('postform.html.twig', ['postForm' => $postForm->createView()]);
    }

    /**
     * @Route("create/post/", name="post_create")
     */
    public function postCreate(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $post = new Post();

        $postForm = $this->createForm(PostType::class, $post);

        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('post_list');
        }

        return $this->render('postform.html.twig', ['postForm' => $postForm->createView()]);
    }

    /**
     * @Route("delete/post/{id}", name="post_delete")
     */
    public function postDelete(
        $id,
        PostRepository $postRepository,
        EntityManagerInterface $entityManagerInterface
    ) {

        $post = $postRepository->find($id);

        // remove supprime le post et flush enregistre dans la base de données
        $entityManagerInterface->remove($post);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("post_list");
    }
}
