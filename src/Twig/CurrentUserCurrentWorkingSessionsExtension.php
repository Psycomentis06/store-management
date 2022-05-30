<?php

namespace App\Twig;

use App\Service\WorkSessionService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CurrentUserCurrentWorkingSessionsExtension extends AbstractExtension
{

    private WorkSessionService $workSessionService;

    public function __construct(WorkSessionService $workSessionService)
    {
        $this->workSessionService = $workSessionService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user_current_working_sessions', [$this, 'currentUserCurrentWorkingSessionsExtension']),
        ];
    }

    public function currentUserCurrentWorkingSessionsExtension(): array
    {
        return $this->workSessionService->getCurrentUserSessions();
    }
}
