<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CustomAbstractController extends AbstractController
{
    private RequestStack $request;

    public function __construct(RequestStack $request)
    {

        $this->request = $request;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->request->getSession()->set('_route', $this->request->getCurrentRequest()->get('_route'));
        return parent::render($view, $parameters, $response);
    }
}