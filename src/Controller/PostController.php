<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
