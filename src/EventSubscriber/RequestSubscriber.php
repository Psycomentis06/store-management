<?php

namespace App\EventSubscriber;

use App\Service\PermissionService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    #[ArrayShape([RequestEvent::class => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }

    public function onKernelRequest(RequestEvent $requestEvent)
    {
        $request = $requestEvent->getRequest();
        $this->permissionService->checkPermission($request, true);
    }

}