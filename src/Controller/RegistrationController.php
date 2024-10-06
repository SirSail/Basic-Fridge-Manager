<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
    
        if ($request->isMethod('POST')) {
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));

            // Szyfrowanie hasła
            $hashedPassword = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);

            // Zapis użytkownika w bazie danych
            $userRepository->save($user, true);

            // Po rejestracji można zalogować użytkownika automatycznie lub przekierować na stronę logowania
            return $this->redirectToRoute('app_login');  // Przekierowanie na stronę logowania
        }

        return $this->render('registration/register.html.twig', [
            'user' => $user,
        ]);
    }
}
