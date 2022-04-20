<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth/')]
class AuthController extends AbstractController
{

    #[Route(
        'login',
        name: 'app_auth_login',
        options: ["system" => "true"],
        defaults: ["description" => "Login in page"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('auth/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route(
        'reset_password',
        name: 'app_auth_reset_password',
        options: ["system" => "true"],
        defaults: ["description" => "Reset user's password by sending a temporary verification key to user's email"]
    )]
    public function resetPassword(): Response
    {
        return $this->render('auth/reset_password.html.twig');
    }
}