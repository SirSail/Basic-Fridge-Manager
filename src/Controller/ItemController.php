<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Category;
use App\Repository\ItemRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_item_list');
    }

    #[Route('/list', name: 'app_item_list', methods: ['GET'])]
    public function list(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAll();

        return $this->render('item/list.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItemRepository $itemRepository, CategoryRepository $categoryRepository): Response
    {
        // Pobierz wszystkie kategorie do wyświetlenia w formularzu
        $categories = $categoryRepository->findAll();

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $categoryId = $request->request->get('category_id');
    
            $item = new Item();
            $item->setName($name);
            $item->setDescription($description);
    
            // Jeśli użytkownik nie wybrał kategorii, ustaw domyślną kategorię
            if ($categoryId) {
                $category = $categoryRepository->find($categoryId);
                $item->setCategory($category);
            } else {
                // Znajdź i ustaw domyślną kategorię, np. "Inne"
                $defaultCategory = $categoryRepository->findOneBy(['name' => 'Inne']);  // lub 'default' category
                $item->setCategory($defaultCategory);
            }
    
            $itemRepository->save($item, true);
    
            return $this->redirectToRoute('app_item_list');
        }

        return $this->render('item/new.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, ItemRepository $itemRepository, CategoryRepository $categoryRepository): Response
    {
        // Pobierz wszystkie kategorie do wyświetlenia w formularzu
        $categories = $categoryRepository->findAll();

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $category = $request->request->get('category_id');

            // Znajdź kategorię po ID
            $category = $categoryRepository->find($category);

            $item->setName($name);
            $item->setDescription($description);
            $item->setCategory($category);

            $itemRepository->save($item, true);

            return $this->redirectToRoute('app_item_list');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'categories' => $categories,
        ]);
    }

    #[Route('/show/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(int $id, ItemRepository $itemRepository): Response
    {
        $item = $itemRepository->find($id);

        if (!$item) {
            $this->addFlash('error', 'Item not found.');
             return $this->redirectToRoute('app_item_list');
        }

    return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $itemRepository->remove($item, true);
        }

        return $this->redirectToRoute('app_item_list');
    }
}

