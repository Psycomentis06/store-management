<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActiveHour extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('active_hour', [$this, 'activeHour']),
        ];
    }

    public function activeHour(): string
    {
        $clientTimeZone = $this->requestStack->getMainRequest()->headers->get('tz_offset');
        $time = new \DateTime();
        if (empty($clientTimeZone)) {
            $timezoneName = timezone_name_from_abbr("", $clientTimeZone * 3600, false);
            $time = new \DateTimeZone($timezoneName);
        } else
            $time = new \DateTimeZone('UTC');
        return ((new \DateTime('now', $time))->format('H:00'));
    }
}
