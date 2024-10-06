<?php

namespace App\Controller;

use App\Entity\UserRole;
use App\Form\UserRoleType;
use App\Repository\UserRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-role')]
class UserRoleController extends AbstractController
{
    #[Route('/list', name: 'app_user_role_list', methods: ['GET'])]
    public function list(UserRoleRepository $userRoleRepository): Response
    {
        $userRoles = $userRoleRepository->findAll();

        return $this->render('user_role/list.html.twig', [
            'userRoles' => $userRoles,
        ]);
    }

    #[Route('/new', name: 'app_user_role_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, UserRepository $userRepository, RoleRepository $roleRepository, UserRoleRepository $userRoleRepository): Response
    {
        if ($request->isMethod('POST')) {
            $userId = $request->request->get('user_id');
            $roleId = $request->request->get('role_id');

            $user = $userRepository->find($userId);
            $role = $roleRepository->find($roleId);

            if (!$user || !$role) {
                $this->addFlash('error', 'Invalid user or role.');
                return $this->redirectToRoute('app_user_role_new');
            }

            $userRole = new UserRole();
            $userRole->setUserId($user);
            $userRole->setRoleId($role);

            // Zapisujemy nową rolę użytkownika
            $userRoleRepository->save($userRole, true);

            return $this->redirectToRoute('app_user_role_list');
        }

        // Pobieranie listy użytkowników i ról do formularza
        $users = $userRepository->findAll();
        $roles = $roleRepository->findAll();

        return $this->render('user_role/new.html.twig', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRole $userRole, UserRoleRepository $userRoleRepository): Response
    {
        if ($request->isMethod('POST')) {
            $userRole->setUserId($request->request->get('user_id'));
            $userRole->setRoleId($request->request->get('role_id'));

            $userRoleRepository->save($userRole, true);

            return $this->redirectToRoute('app_user_role_list');
        }

        return $this->render('user_role/edit.html.twig', [
            'userRole' => $userRole,
        ]);
    }

    #[Route('/{id}', name: 'app_user_role_delete', methods: ['POST'])]
    public function delete(Request $request, UserRole $userRole, UserRoleRepository $userRoleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userRole->getId(), $request->request->get('_token'))) {
            $userRoleRepository->remove($userRole, true);
        }

        return $this->redirectToRoute('app_user_role_list');
    }
}
