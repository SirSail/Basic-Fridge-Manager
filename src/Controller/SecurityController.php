<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Pobranie błędu logowania, jeśli wystąpił
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Wyświetlenie formularza logowania z błędami (jeśli istnieją)
        return $this->render('security/login.html.twig', [
             'last_username' => $lastUsername,
             'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout(): void
    {
        // Symfony sam obsługuje proces wylogowania, więc metoda może być pusta
        throw new \Exception('Ensure you have configured the logout path in security.yaml');
    }
}
