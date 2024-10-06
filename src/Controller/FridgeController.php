<?php

namespace App\Controller;

use App\Entity\Fridge;
use App\Repository\FridgeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fridge')]
class FridgeController extends AbstractController
{
    #[Route('/list', name: 'app_fridge_list', methods: ['GET'])]
    public function list(FridgeRepository $fridgeRepository): Response
    {
        $fridges = $fridgeRepository->findAll();
    
        return $this->render('fridge/list.html.twig', [
            'fridges' => $fridges,
            'error' => null,  // Dodanie zmiennej 'error' jako null
        ]);
    }

    #[Route('/new', name: 'app_fridge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FridgeRepository $fridgeRepository): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $location = $request->request->get('location');

            // Tworzenie nowego obiektu Fridge
            $fridge = new Fridge();
            $fridge->setName($name);
            $fridge->setLocation($location);

            // Zapisanie lodówki do bazy danych
            $fridgeRepository->save($fridge, true);

            return $this->redirectToRoute('app_fridge_list');
        }

        return $this->render('fridge/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'app_fridge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, FridgeRepository $fridgeRepository): Response
    {
        $fridge = $fridgeRepository->find($id);

        if (!$fridge) {
            $this->addFlash('error', 'Fridge not found.');
            return $this->redirectToRoute('app_fridge_list');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $location = $request->request->get('location');

            // Ustawienie nowych wartości w obiekcie Fridge
            $fridge->setName($name);
            $fridge->setLocation($location);

            // Zapisanie zmian
            $fridgeRepository->save($fridge, true);

            return $this->redirectToRoute('app_fridge_list');
        }

        return $this->render('fridge/edit.html.twig', [
            'fridge' => $fridge,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_fridge_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, FridgeRepository $fridgeRepository): Response
    {
        $fridge = $fridgeRepository->find($id);
    
        if (!$fridge) {
            $this->addFlash('error', 'Fridge not found.');
            return $this->redirectToRoute('app_fridge_list');
        }
    
        if ($this->isCsrfTokenValid('delete'.$fridge->getId(), $request->request->get('_token'))) {
            $fridgeRepository->remove($fridge, true);
        }
    
        return $this->redirectToRoute('app_fridge_list');
    }
}


