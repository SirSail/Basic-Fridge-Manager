<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user2')]
class UserController2 extends AbstractController
{
    #[Route('/list', name: 'app_user_list2', methods: ['GET'])]
    public function list(UserRepository $userRepository): Response
    {
        
        $users = $userRepository->findAll();

        return $this->render('user2/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        if ($request->isMethod('POST')) {
            $user->setUsername($request->request->get('username'));
            $user->setPassword($request->request->get('password'));
            $user->setEmail($request->request->get('email'));

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_list2');
        }

        return $this->render('user2/new.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_user_list2');
        }

        return $this->render('user2/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('password'));

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_list2');
        }

        return $this->render('user2/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_list2');
    }
}


