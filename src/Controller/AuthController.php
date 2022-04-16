<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth/')]
class AuthController extends AbstractController
{

    #[Route('login', name: 'app_login_default')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }
}