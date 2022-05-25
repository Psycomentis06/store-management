<?php

namespace App\Twig;

use App\Service\PermissionService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CanAccessRouteExtension extends AbstractExtension
{
    private PermissionService $permissionService;
    private RequestStack $requestStack;

    public function __construct(PermissionService $permissionService, RequestStack $requestStack)
    {
        $this->permissionService = $permissionService;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('can_access_route', [$this, 'canAccessRoute']),
        ];
    }

    public function canAccessRoute($routeName): bool
    {
        return $this->permissionService->checkPermission($this->requestStack->getMainRequest(), routeName: $routeName);
    }
}
