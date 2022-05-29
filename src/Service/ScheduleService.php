<?php

namespace App\Service;

use App\Entity\Schedule;
use App\Repository\WorkEventRepository;

class ScheduleService
{
    private WorkEventRepository $workEventRepository;

    public function __construct(WorkEventRepository $workEventRepository)
    {
        $this->workEventRepository = $workEventRepository;
    }

    public function minifyData(Schedule $schedule): array
    {
        $arrayStructure = [
            0 => [
                '9:00' => [
                    'event' => [],
                    'session' => [],
                ],
            ],
            1 => [],
        ];

        $dayNames = array(
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        );
        $res = [];
        // Loop throw week days
        for ($i = 0; $i < 6; $i++) {
            $dayArray = [];
            $sessions = $schedule->getSessions();
            $events = $this->workEventRepository->findByCurrentWeekAndSchedule($schedule);
            foreach ($events as $event) {
                $eventDay = $event->getFromDate()->format('l');
                if ($dayNames[$eventDay] === $i) {
                    $eventTimeStart = $event->getFromDate()->format('H:i');
                    $dayArray[$i][$eventTimeStart] = $event;
                }
            }

            foreach ($sessions as $session) {
                if (in_array($i, $session->getDays())) {
                    $dayArray[$i][$session->getFromTime()->format('H:i')] = $session;
                }
            }
            $res[] = $dayArray;
        }
        return $res;
    }
}