<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/categories", name="category_list")
     */
    public function categoryList(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render("categories.html.twig", ['categories' => $categories]);
    }

    /**
     * @Route("/category/{id}", name="category_show")
     */
    public function categoryShow($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render("category.html.twig", ['category' => $category]);
    }

    /**
     * @Route("/update/category/{id}", name="update_category")
     */
    public function updateCategory(
        $id,
        CategoryRepository $categoryRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {

        $category = $categoryRepository->find($id);

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'La catégorie est modifiée'
            );

            return $this->redirectToRoute("category_list");
        }

        return $this->render("categoryform.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    /**
     * @Route("create/category", name="create_category")
     */
    public function createCategory(Request $request, EntityManagerInterface $entityManagerInterface)
    {

        $category = new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'La catégorie est ajoutée'
            );

            return $this->redirectToRoute("category_list");
        }

        return $this->render("categoryform.html.twig", ['categoryForm' => $categoryForm->createView()]);
    }

    /**
     * @Route("delete/category/{id}", name="delete_category")
     */
    public function deleteCategory(
        $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManagerInterface
    ) {
        $category = $categoryRepository->find($id);

        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'La catégorie est supprimée'
        );

        return $this->redirectToRoute("category_list");
    }
}
