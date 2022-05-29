<?php

namespace App\Controller\Main;

use App\Controller\CustomAbstractController;
use App\Entity\Inventory;
use App\Entity\Schedule;
use App\Entity\Store;
use App\Entity\WorkEvent;
use App\Entity\WorkSession;
use App\Form\StoreType;
use App\Form\WorkEventType;
use App\Form\WorkSessionType;
use App\Repository\InventoryRepository;
use App\Repository\ScheduleRepository;
use App\Repository\StoreRepository;
use App\Repository\WorkEventRepository;
use App\Repository\WorkSessionRepository;
use App\Service\ScheduleService;
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
    public function new(Request $request, StoreRepository $storeRepository, InventoryRepository $inventoryRepository, ScheduleRepository $scheduleRepository): Response
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
            $schedule = new Schedule();
            $scheduleRepository->add($schedule);
            $this->addFlash('success', 'Schedule created');
            $inventory = new Inventory();
            $store
                ->setSchedule($schedule)
                ->setInventory($inventory);

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

        $id = $store->getId();
        if ($this->isCsrfTokenValid('delete' . $store->getId(), $request->request->get('_token'))) {
            $storeRepository->remove($store);
            $this->addFlash('success', 'Store #' . $id . ' removed');
        }

        return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(
        '/{id}/schedule/',
        name: 'app_schedule_show',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Schedule Table for given Store",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])
    ]
    public function storeSchedule(Request $request, WorkEventRepository $workEventRepository, WorkSessionRepository $workSessionRepository, Schedule $schedule, ScheduleService $scheduleService): Response
    {
        $workEvent = new WorkEvent();
        $createEventForm = $this->createForm(WorkEventType::class, $workEvent);

        $createEventForm->handleRequest($request);
        if ($createEventForm->isSubmitted()) {
            if ($createEventForm->isValid()) {
                $workEvent->addSchedule($schedule);
                $workEventRepository->add($workEvent);
                $this->addFlash('success', 'New event is created');
            } else {
                $this->addFlash('error', 'Creating new event is failed for more info click \'Add New Event\' button for more info');
            }
        }

        $workSession = new WorkSession();

        $createSessionForm = $this->createForm(WorkSessionType::class, $workSession);
        $createSessionForm->handleRequest($request);
        if ($createSessionForm->isSubmitted()) {
            if ($createSessionForm->isValid()) {
                $workSession->setSchedule($schedule);
                $workSessionRepository->add($workSession);
                $this->addFlash('success', 'New Session is created');
            } else {
                $this->addFlash('error', 'Creating new Session was failed. for more info click \' Add New Session \' button ');
            }
        }

        $scheduleOrganised = $scheduleService->organizeData($schedule);
        return $this->render('schedule/show.html.twig', [
            'schedule' => $schedule,
            'eventForm' => $createEventForm->createView(),
            'sessionForm' => $createSessionForm->createView(),
            'data' => $scheduleOrganised,
            'allow_edit' => true,
            'allow_delete' => true
        ]);
    }

    #[Route(
        '/work_session/{id}',
        name: 'app_schedule_session_edit',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Edit Given schedule session",
            "role" => "superadmin"
        ],
        methods: ['GET', 'POST'])
    ]
    function editSession(Request $request, WorkSession $workSession, WorkSessionRepository $workSessionRepository): Response
    {
        $form = $this->createForm(WorkSessionType::class, $workSession);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $workSessionRepository->add($workSession);
            $this->addFlash('success', 'Session edited successfully');
        }

        return $this->render('schedule/edit-session.html.twig', [
            'form' => $form->createView(),
            'session' => $workSession
        ]);
    }

    #[Route(
        '/work_session/{id}',
        name: 'app_schedule_session_delete',
        options: [
            "system" => 'false'
        ],
        defaults: [
            "description" => "Delete Given schedule session",
            "role" => "superadmin"
        ],
        methods: ['POST'])
    ]
    public function deleteSession(Request $request, WorkSession $workSession, WorkSessionRepository $workSessionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $workSession->getId(), $request->request->get('_token'))) {
            $workSessionRepository->remove($workSession);
            $this->addFlash('success', 'Customer removed');
        }

        return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER);
    }
}
