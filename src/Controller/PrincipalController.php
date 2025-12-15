<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class PrincipalController extends AbstractController
{
    #[Route('/principal', name: 'app_principal')]
    public function index(): Response
    {
        return $this->render('principal/index.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    #[Route('/login', name: 'app_auth')]
    public function auth(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    // Route unique pour le dashboard
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_auth');
        }

        // On peut envoyer le rôle à Twig pour afficher dynamiquement les menus
        $roles = $user->getRoles();

        return $this->render('principal/dashboard.html.twig', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        return $this->render('Form/InscriptionType.html.twig');
    }
}
