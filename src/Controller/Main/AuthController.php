<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth/')]
class AuthController extends CustomAbstractController
{

    #[Route(
        'login',
        name: 'app_auth_login',
        options: ["system" => "true"],
        defaults: ["description" => "Login in page"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('main/auth/login.html.twig', [
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
        return $this->render('main/auth/reset_password.html.twig');
    }

    #[Route(
        'auto_auth',
        name: 'app_auth_auto_auth',
        options: ["system" => "true"],
        defaults: ["description" => "Automatically authenticate user based on tokens"]
    )]
    public function autoAuth(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render(
            'main/auth/error/single_session.html.twig',
            [
                'error' => $authenticationUtils->getLastAuthenticationError(),
                'last_username' => $authenticationUtils->getLastUsername(),
            ]);
    }
}