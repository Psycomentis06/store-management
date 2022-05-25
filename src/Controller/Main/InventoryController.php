<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Inventory;
use App\Form\InventoryType;
use App\Repository\InventoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        '/new',
        name: 'app_inventory_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new inventory",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST']
    )]
    public function new(Request $request, InventoryRepository $inventoryRepository): Response
    {
        $inventory = new Inventory();
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryRepository->add($inventory);
            return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory/new.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
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

    #[Route(
        '/{id}/edit',
        name: 'app_inventory_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given inventory",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Inventory $inventory, InventoryRepository $inventoryRepository): Response
    {
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventoryRepository->add($inventory);
            return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_inventory_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete given inventory",
            "role" => "superadmin"
        ],
        methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, InventoryRepository $inventoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inventory->getId(), $request->request->get('_token'))) {
            $inventoryRepository->remove($inventory);
        }

        return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
    }
}
