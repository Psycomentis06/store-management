<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/config')]
class ConfigController extends AbstractController
{
    #[Route(
        '/default_store',
        name: 'config_default_store',
        options: ["system" => "false"],
        defaults: [
            "description" => "Set Default store for logged user",
            "role" => ""
        ],
        methods: ['POST'],
    )]
    public function setDefaultStore(Request $request): Response
    {
        $defaultStoreId = $request->request->get('_default-store-form-select');
        if (empty($defaultStoreId)) {
            $this->addFlash('error', 'No store id is given');
        } else {
            $session = $request->getSession();
            $session->set('_default-store', $defaultStoreId);
            $this->addFlash('success', 'Default store is set to ' . $defaultStoreId);
        }
        return $this->redirectToRoute('app_my_store');
    }
}