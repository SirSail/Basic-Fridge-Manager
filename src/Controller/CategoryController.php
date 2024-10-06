<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/list', name: 'app_category_list', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            
            // Tworzenie nowej kategorii
            $category = new Category();
            $category->setName($name);

            // Zapis do bazy danych
            $categoryRepository->save($category, true);

            // Przekierowanie na listę
            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('category/new.html.twig');
    }

    #[Route('/edit/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            $this->addFlash('error', 'Category not found.');
            return $this->redirectToRoute('app_category_list');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $category->setName($name);

            // Zapis do bazy danych
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_category_list');
    }
}

