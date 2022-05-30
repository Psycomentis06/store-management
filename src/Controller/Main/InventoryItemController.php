<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\InventoryItem;
use App\Form\InventoryItemType;
use App\Repository\InventoryItemRepository;
use App\Repository\InventoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inventory')]
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

    #[Route('/{id}/new',
        name: 'app_inventory_item_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new currency",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, int $id, InventoryRepository $inventoryRepository, InventoryItemRepository $inventoryItemRepository): Response
    {
        $inventoryItem = new InventoryItem();
        $inventory = $inventoryRepository->find($id);
        if (empty($inventory))
            throw new NotFoundHttpException('Inventory ' . $id . ' not found');
        $inventoryItem->setInventory($inventory);
        $form = $this->createForm(InventoryItemType::class, $inventoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryItemRepository->add($inventoryItem);
            $this->addFlash('success', 'Inventory Item is created for inventory #' . $id);
            return $this->redirectToRoute('app_inventory_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory_item/new.html.twig', [
            'inventory_item' => $inventoryItem,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{inventoryId}/{inventoryItemId}/edit',
        name: 'app_inventory_item_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given inventory item",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, int $inventoryId, int $inventoryItemId, InventoryItemRepository $inventoryItemRepository): Response
    {
        $inventoryItem = $inventoryItemRepository->find($inventoryItemId);
        if (empty($inventoryItem))
            throw new NotFoundHttpException('Inventory Item #' . $inventoryItemId . ' Not Found');
        $form = $this->createForm(InventoryItemType::class, $inventoryItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryItemRepository->add($inventoryItem);
            $this->addFlash('success', 'Item #' . $inventoryItemId . ' updated');
            return $this->redirectToRoute('app_inventory_show', ['id' => $inventoryId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory_item/edit.html.twig', [
            'inventory_item' => $inventoryItem,
            'inventory_id' => $inventoryId,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{inventoryId}/{inventoryItemId}/delete',
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
    public function delete(Request $request, int $inventoryId, int $inventoryItemId, InventoryItemRepository $inventoryItemRepository): Response
    {
        $inventoryItem = $inventoryItemRepository->find($inventoryItemId);
        if (empty($inventoryItem))
            throw new  NotFoundHttpException('Inventory Item #' . $inventoryItemId . ' Not Found');
        if ($this->isCsrfTokenValid('delete' . $inventoryItem->getId(), $request->request->get('_token'))) {
            $inventoryItemRepository->remove($inventoryItem);
            $this->addFlash('success', 'Item #' . $inventoryItemId . ' removed');
        }

        return $this->redirectToRoute('app_inventory_show', ['id' => $inventoryId], Response::HTTP_SEE_OTHER);
    }
}
