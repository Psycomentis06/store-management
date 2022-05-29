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

    public function organizeData(Schedule $schedule): array
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
        for ($i = 0; $i <= 6; $i++) {
            $dayArray = [];
            $sessions = $schedule->getSessions();
            $events = $this->workEventRepository->findByCurrentWeekAndSchedule($schedule);
            foreach ($events as $event) {
                $eventDay = $event->getFromDate()->format('l');
                if ($dayNames[$eventDay] === $i) {
                    $eventTimeStart = $event->getFromDate()->format('H');
                    $eventTimeEnd = $event->getToDate()->format('H');
                    $eventTimeEndDay = $event->getToDate()->format('l');
                    $startDayNumber = $dayNames[$eventDay];
                    $endDayNumber = $dayNames[$eventTimeEndDay];

                    $eventDateRangeIndex = 0;
                    while ($endDayNumber - $startDayNumber >= 0) {
                        while ($eventDateRangeIndex <= $eventTimeEnd) {
                            $sessionRangeTime = '';
                            if ($eventDateRangeIndex < 10)
                                $sessionRangeTime = '0' . ($eventDateRangeIndex + 0) . ':00';
                            else
                                $sessionRangeTime = $eventDateRangeIndex . ':00';
                            $dayArray[$sessionRangeTime] = ['obj' => $event, 'event' => true];
                            $eventDateRangeIndex++;
                        }
                        $res[$startDayNumber] = $dayArray;
                        $startDayNumber++;
                    }

                }
            }

            foreach ($sessions as $session) {
                if (in_array($i, $session->getDays())) {
                    $sessionFormHour = $session->getFromTime()->format('H');
                    $sessionToHour = $session->getToTime()->format('H');
                    $sessionRangeIndex = $sessionFormHour;
                    while ($sessionRangeIndex <= $sessionToHour) {
                        $sessionRangeTime = '';
                        if ($sessionRangeIndex < 10)
                            $sessionRangeTime = '0' . ($sessionRangeIndex + 0) . ':00';
                        else
                            $sessionRangeTime = $sessionRangeIndex . ':00';
                        $dayArray[$sessionRangeTime] = ['obj' => $session, 'event' => false];
                        $sessionRangeIndex++;
                    }
                }
            }
            $res[$i] = $dayArray;
        }
        return $res;
    }
}