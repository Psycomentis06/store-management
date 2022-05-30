<?php

namespace App\Service;

use App\Entity\WorkSession;
use App\Utils\Days;

class WorkSessionService
{

    private TimeService $timeService;

    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
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