<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Routes;
use Doctrine\ORM\NonUniqueResultException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

class RequestSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private UserRepository $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
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
        $this->checkPermission($request);
    }

    private function checkPermission(Request $request)
    {
        $currentUser = $this->security->getUser();
        $routeRole = $request->get('role');
        if (!empty($routeRole)) {
            if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new UnauthorizedHttpException('LOGIN', "Login required to access this route");
            } else {
                if ($currentUser instanceof User) {
                    $currentRoutePermission = Routes::getPermissionName($request->get('_controller'));
                    /*
                     * Version N°1
                     * try {
                        $userPermissions = $this->userRepository->findOneByPermissionName($currentUser->getId(), $currentRoutePermission);
                        if (empty($userPermissions))
                            throw new AccessDeniedHttpException("No permission to access this page");
                    } catch (NonUniqueResultException $e) {
                    }*/

                    /**
                     * Ver 2
                     */
                    if (!in_array($currentRoutePermission, $currentUser->getPermissions()))
                        throw new AccessDeniedHttpException("No permission to access this page");
                }
            }
        }
    }
}