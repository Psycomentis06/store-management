<?php

namespace App\Twig;

use App\Service\TimeService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActiveHour extends AbstractExtension
{
    private RequestStack $requestStack;
    private TimeService $timeService;

    public function __construct(RequestStack $requestStack, TimeService $timeService)
    {
        $this->requestStack = $requestStack;
        $this->timeService = $timeService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('active_hour', [$this, 'activeHour']),
        ];
    }

    public function activeHour(): string
    {
        return $this->timeService->getUserTimeZone()->format('H:00');
    }
}
