<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     */
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }

    /**
     * @Route("/tags/", name="tag_list")
     */
    public function tagList(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();

        return $this->render("tags.html.twig", ['tags' => $tags]);
    }

    /**
     * @Route("tag/{id}", name="tag_show")
     */
    public function tagShow($id, TagRepository $tagRepository)
    {
        $tag = $tagRepository->find($id);

        return $this->render("tag.html.twig", ['tag' => $tag]);
    }

    /**
     * @Route("update/tag/{id}", name="update_tag")
     */
    public function updateTag(
        $id,
        TagRepository $tagRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $tag = $tagRepository->find($id);

        $tagForm = $this->createForm(TagType::class, $tag);

        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('tag_list');
        }

        return $this->render("tagform.html.twig", ['tagForm' => $tagForm->createView()]);
    }

    /**
     * @Route("create/tag/", name="create_tag")
     */
    public function createTag(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $tag = new Tag();

        $tagForm = $this->createForm(TagType::class, $tag);

        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("tag_list");
        }

        return $this->render("tagform.html.twig", ['tagForm' => $tagForm->createView()]);
    }

    /**
     * @Route("delete/tag/{id}", name="delete_tag")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManagerInterface)
    {
        $tag = $tagRepository->find($id);

        $entityManagerInterface->remove($tag);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("tag_list");
    }
}
