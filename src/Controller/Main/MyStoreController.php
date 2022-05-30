<?php

namespace App\Controller\Main;

use App\Repository\StoreRepository;
use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/work')]
class MyStoreController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(
        '/my_store',
        name: 'app_my_store',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Display store information related to current working session",
            "role" => "user"
        ],
        methods: ['GET']
    )]
    public function myStore(Request $request, StoreRepository $storeRepository): Response
    {
        $session = $request->getSession();
        $storeId = $session->get('_default-store');
        $store = null;
        if (empty($storeId)) {
            $this->addFlash('warning', 'No Store is set by default it\'s either you don\'t have an actual active session to select  ');
        } else {
            $store = $storeRepository->find($storeId);
            if (empty($store)) {
                $this->addFlash('error', 'No Store found with given id "' . $storeId . '"');
            }
        }

        return $this->render('main/work/my_store-html.twig', [
            'store' => $store
        ]);
    }

    #[Route(
        '/my_schedule',
        name: 'app_my_schedule',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Display Schedule information related to current working session",
            "role" => "user"
        ],
        methods: ['GET']
    )]
    public function mySchedule(Request $request, StoreRepository $storeRepository, ScheduleService $scheduleService): Response
    {
        $session = $request->getSession();
        $storeId = $session->get('_default-store');
        $store = null;
        $scheduleOrganised = null;
        if (empty($storeId)) {
            $this->addFlash('warning', 'No Store is set by default it\'s either you don\'t have an actual active session to select  ');
        } else {
            $store = $storeRepository->find($storeId);
            if (empty($store)) {
                $this->addFlash('error', 'No Store found with given id "' . $storeId . '"');
            } else {
                $scheduleOrganised = $scheduleService->organizeData($store->getSchedule());
            }
        }

        return $this->render('main/work/my_schedule.html.twig', [
            'data' => $scheduleOrganised
        ]);
    }

    #[Route(
        '/my_store_inventory',
        name: 'app_my_inventory',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Display store inventory information related to current working session",
            "role" => "user"
        ],
        methods: ['GET']
    )]
    public function myStoreInventory(Request $request, StoreRepository $storeRepository): Response
    {
        $session = $request->getSession();
        $storeId = $session->get('_default-store');
        $store = null;
        $inventory = null;
        if (empty($storeId)) {
            $this->addFlash('warning', 'No Store is set by default it\'s either you don\'t have an actual active session to select  ');
        } else {
            $store = $storeRepository->find($storeId);
            if (empty($store)) {
                $this->addFlash('error', 'No Store found with given id "' . $storeId . '"');
            } else {
                $inventory = $store->getInventory();
                //dd($inventory->getId());
            }
        }

        return $this->render('main/work/my_inventory.html.twig', [
            'inventory' => $inventory
        ]);
    }
}