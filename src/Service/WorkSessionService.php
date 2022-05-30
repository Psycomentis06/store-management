<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\WorkSession;
use App\Repository\WorkSessionRepository;
use App\Utils\Days;
use Symfony\Component\Security\Core\Security;

class WorkSessionService
{

    private TimeService $timeService;
    private Security $security;
    private WorkSessionRepository $workSessionRepository;

    public function __construct(TimeService $timeService, Security $security, WorkSessionRepository $workSessionRepository)
    {
        $this->timeService = $timeService;
        $this->security = $security;
        $this->workSessionRepository = $workSessionRepository;
    }

    public function getCurrentUserSessions(): array
    {
        $currUser = $this->security->getUser();
        $currentSessions = [];
        if (!empty($currUser) && $currUser instanceof User) {
            $sessions = $this->workSessionRepository->findAllByUserAndCurrentTime($currUser, $this->timeService->getUserTimeZone());
            $currentSessions = $this->filterCurrentSession($sessions);
        }
        return $currentSessions;
    }

    /**
     * @param array<WorkSession> $sessions
     * @return array<WorkSession>
     */
    public function filterCurrentSession(array $sessions): array
    {
        $todayIndex = Days::get((new \DateTime())->format('l'));
        return array_filter($sessions, function ($session) use ($todayIndex) {
            return in_array($todayIndex, $session->getDays());
        });
    }

}