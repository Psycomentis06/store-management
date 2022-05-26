<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Inventory;
use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\InventoryRepository;
use App\Repository\StoreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/store')]
class StoreController extends CustomAbstractController
{
    #[Route(
        '/',
        name: 'app_store_index',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "List all stores",
            "role" => "user"
        ],
        methods: ['GET'])]
    public function index(StoreRepository $storeRepository): Response
    {
        return $this->render('store/index.html.twig', [
            'stores' => $storeRepository->findAll(),
        ]);
    }

    #[Route(
        '/new',
        name: 'app_store_new',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Create new store",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function new(Request $request, StoreRepository $storeRepository, InventoryRepository $inventoryRepository): Response
    {
        $storeAddress = [
            ['key' => 'Country', 'value' => ''],
            ['key' => 'City', 'value' => ''],
            ['key' => 'Address', 'value' => ''],
        ];
        $store = (new Store())
            ->setAddress($storeAddress);
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inventory = (new Inventory())
                ->setStore($store);
            $storeRepository->add($store);
            $this->addFlash('success', 'Store created');
            $inventoryRepository->add($inventory);
            $this->addFlash('success', 'Inventory created for store#' . $store->getId());
            return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('store/new.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_store_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Detailed info for given Store",
            "role" => "superadmin"
        ],
        methods: ['GET'])]
    public function show(Store $store): Response
    {
        return $this->render('store/show.html.twig', [
            'store' => $store,
        ]);
    }

    #[Route(
        '/{id}/edit',
        name: 'app_store_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit given Store",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Store $store, StoreRepository $storeRepository): Response
    {
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $storeRepository->add($store);
            $this->addFlash('success', 'Store #' . $store->getId() . ' edited');
            return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('store/edit.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route(
        '/{id}',
        name: 'app_store_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete given Store",
            "role" => "superadmin"
        ],
        methods: ['POST'])]
    public function delete(Request $request, Store $store, StoreRepository $storeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $store->getId(), $request->request->get('_token'))) {
            $storeRepository->remove($store);
            $this->addFlash('success', 'Store #' . $store->getId() . ' removed');
        }

        return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER);
    }
}
