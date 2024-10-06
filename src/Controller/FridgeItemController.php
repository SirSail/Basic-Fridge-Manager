<?php

namespace App\Controller;

use App\Entity\FridgeItem;
use App\Repository\FridgeItemRepository;
use App\Repository\FridgeRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fridge-item')]
class FridgeItemController extends AbstractController
{
    #[Route('/list', name: 'app_fridge_item_list', methods: ['GET'])]
    public function list(FridgeItemRepository $fridgeItemRepository): Response
    {
        $fridgeItems = $fridgeItemRepository->findAll();

        return $this->render('fridge_item/list.html.twig', [
            'fridgeItems' => $fridgeItems,
        ]);
    }

    #[Route('/new', name: 'app_fridge_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FridgeItemRepository $fridgeItemRepository, FridgeRepository $fridgeRepository, ItemRepository $itemRepository): Response
    {
        if ($request->isMethod('POST')) {
            $fridge_id = $request->request->get('fridge_id');
            $item_id = $request->request->get('item_id');
            $quantity = $request->request->get('quantity');
            $expiration_date = $request->request->get('expiration_date');

            $fridge = $fridgeRepository->find($fridge_id);
            $item = $itemRepository->find($item_id);

            $fridgeItem = new FridgeItem();
            $fridgeItem->setFridge($fridge); 
            $fridgeItem->setItem($item);
            $fridgeItem->setQuantity($quantity);
            $fridgeItem->setExpirationDate(new \DateTime($expiration_date));

            $fridgeItemRepository->save($fridgeItem, true);

            return $this->redirectToRoute('app_fridge_item_list');
        }

        return $this->render('fridge_item/new.html.twig', [
            'fridges' => $fridgeRepository->findAll(),
            'items' => $itemRepository->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_fridge_item_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, FridgeItemRepository $fridgeItemRepository, FridgeRepository $fridgeRepository, ItemRepository $itemRepository): Response
    {
        $fridgeItem = $fridgeItemRepository->find($id);

        if (!$fridgeItem) {
            $this->addFlash('error', 'Fridge item not found.');
            return $this->redirectToRoute('app_fridge_item_list');
        }

        if ($request->isMethod('POST')) {
            $fridge_id = $request->request->get('fridge_id');
            $item_id = $request->request->get('item_id');
            $quantity = $request->request->get('quantity');
            $expiration_date = $request->request->get('expiration_date');

            $fridge = $fridgeRepository->find($fridge_id);
            $item = $itemRepository->find($item_id);

            $fridgeItem->setFridge($fridge);
            $fridgeItem->setItem($item);
            $fridgeItem->setQuantity($quantity);
            $fridgeItem->setExpirationDate(new \DateTime($expiration_date));

            $fridgeItemRepository->save($fridgeItem, true);

            return $this->redirectToRoute('app_fridge_item_list');
        }

        return $this->render('fridge_item/edit.html.twig', [
            'fridgeItem' => $fridgeItem,
            'fridges' => $fridgeRepository->findAll(),
            'items' => $itemRepository->findAll(),
        ]);
    }
    #[Route('/show/{id}', name: 'app_fridge_item_show', methods: ['GET'])]
    public function show(int $id, FridgeItemRepository $fridgeItemRepository): Response
    {
        $fridgeItem = $fridgeItemRepository->find($id);

        if (!$fridgeItem) {
            $this->addFlash('error', 'Fridge item not found.');
            return $this->redirectToRoute('app_fridge_item_list');
        }

        return $this->render('fridge_item/show.html.twig', [
            'fridgeItem' => $fridgeItem,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_fridge_item_delete', methods: ['POST'])]
    public function delete(Request $request, FridgeItem $fridgeItem, FridgeItemRepository $fridgeItemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fridgeItem->getId(), $request->request->get('_token'))) {
            $fridgeItemRepository->remove($fridgeItem, true);
        }

        return $this->redirectToRoute('app_fridge_item_list');
    }
}

