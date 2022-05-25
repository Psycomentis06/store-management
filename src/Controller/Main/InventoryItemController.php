<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\InventoryItem;
use App\Form\InventoryItemType;
use App\Repository\InventoryItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inventory/item')]
class InventoryItemController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_inventory_item_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "All inventory items",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function index(InventoryItemRepository $inventoryItemRepository): Response
    {
        return $this->render('inventory_item/index.html.twig', [
            'inventory_items' => $inventoryItemRepository->findAll(),
        ]);
    }

    #[Route('/new',
        name: 'app_inventory_item_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new currency",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, InventoryItemRepository $inventoryItemRepository): Response
    {
        $inventoryItem = new InventoryItem();
        $form = $this->createForm(InventoryItemType::class, $inventoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryItemRepository->add($inventoryItem);
            return $this->redirectToRoute('app_inventory_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory_item/new.html.twig', [
            'inventory_item' => $inventoryItem,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_inventory_item_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info for a given inventory items",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function show(InventoryItem $inventoryItem): Response
    {
        return $this->render('inventory_item/show.html.twig', [
            'inventory_item' => $inventoryItem,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_inventory_item_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given inventory item",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, InventoryItem $inventoryItem, InventoryItemRepository $inventoryItemRepository): Response
    {
        $form = $this->createForm(InventoryItemType::class, $inventoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryItemRepository->add($inventoryItem);
            return $this->redirectToRoute('app_inventory_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory_item/edit.html.twig', [
            'inventory_item' => $inventoryItem,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_inventory_item_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete given inventory item",
            "role" => "superadmin"
        ],
        methods: ['POST'])
    ]
    public function delete(Request $request, InventoryItem $inventoryItem, InventoryItemRepository $inventoryItemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inventoryItem->getId(), $request->request->get('_token'))) {
            $inventoryItemRepository->remove($inventoryItem);
        }

        return $this->redirectToRoute('app_inventory_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
