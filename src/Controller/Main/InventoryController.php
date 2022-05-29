<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inventory')]
class InventoryController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_inventory_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "List page for all inventories",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function index(InventoryRepository $inventoryRepository): Response
    {
        return $this->render('inventory/index.html.twig', [
            'inventories' => $inventoryRepository->findAll(),
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_inventory_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed inventory",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function show(Inventory $inventory): Response
    {
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
        ]);
    }
}
