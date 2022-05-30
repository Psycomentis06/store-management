<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class TimeService
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getUserTimeZone()
    {
        $clientTimeZone = $this->requestStack->getMainRequest()->headers->get('tz_offset');
        $time = new \DateTime();
        if (empty($clientTimeZone)) {
            $timezoneName = timezone_name_from_abbr("", $clientTimeZone * 3600, false);
            $time = new \DateTimeZone($timezoneName);
        } else
            $time = new \DateTimeZone('UTC');
        return ((new \DateTime('now', $time)));
    }
}