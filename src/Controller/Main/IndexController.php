<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_index',
        options: ["system" => "true"],
        defaults: ["description" => "Application's default index route (home page route)"],
    )]
    public function index(): Response
    {
        return $this->render('main/base.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route(
        '/login',
        name: 'app_login',
        options: ["system" => "true"],
        defaults: ["description" => "Redirect users to the real login page on url '/auth/login'"]
    )]
    public function login(): Response
    {
        return $this->redirectToRoute('app_auth_login');
    }
}
