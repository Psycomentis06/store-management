<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Repository\WorkSessionRepository;
use App\Service\TimeService;
use App\Service\WorkSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function myStore(WorkSessionRepository $workSessionRepository, TimeService $timeService, WorkSessionService $workSessionService)
    {
        $currUser = $this->getUser();
        $currentSessions = [];
        if (!empty($currUser) && $currUser instanceof User) {
            $sessions = $workSessionRepository->findAllByUserAndCurrentTime($currUser, $timeService->getUserTimeZone());
            $currentSessions = $workSessionService->filterCurrentSession($sessions);
        }
        return $this->render('main/work/my_store-html.twig', [
            'sessions' => $currentSessions
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
    public function mySchedule(WorkSessionRepository $workSessionRepository, TimeService $timeService, WorkSessionService $workSessionService): \Symfony\Component\HttpFoundation\Response
    {
        $currUser = $this->getUser();
        $currentSessions = [];
        if (!empty($currUser) && $currUser instanceof User) {
            $sessions = $workSessionRepository->findAllByUserAndCurrentTime($currUser, $timeService->getUserTimeZone());
            $currentSessions = $workSessionService->filterCurrentSession($sessions);
        }
        return $this->render('main/work/my_schedule.html.twig', [
            'sessions' => $currentSessions
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
    public function myStoreInventory(WorkSessionRepository $workSessionRepository, TimeService $timeService, WorkSessionService $workSessionService): \Symfony\Component\HttpFoundation\Response
    {
        $currUser = $this->getUser();
        $currentSessions = [];
        if (!empty($currUser) && $currUser instanceof User) {
            $sessions = $workSessionRepository->findAllByUserAndCurrentTime($currUser, $timeService->getUserTimeZone());
            $currentSessions = $workSessionService->filterCurrentSession($sessions);
        }
        return $this->render('main/work/my_inventory.html.twig', [
            'sessions' => $currentSessions
        ]);
    }
}