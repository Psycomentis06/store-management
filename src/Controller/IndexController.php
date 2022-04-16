<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('/user/base.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response {
        return $this->redirectToRoute('app_login_default');
    }
}
