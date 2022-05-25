<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\Routes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

class PermissionService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function checkPermission(Request $request, bool $throwable = false, string $routeName = null): bool
    {
        $currentUser = $this->security->getUser();
        $routeRole = $request->get('role');
        if (!empty($routeRole)) {
            if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                if ($throwable)
                    throw new UnauthorizedHttpException('LOGIN', "Login required to access this route");
                else
                    return false;
            } else {
                if ($currentUser instanceof User) {
                    $currentRoutePermission = "";
                    if (empty($routeName))
                        $currentRoutePermission = Routes::getPermissionName($request->get('_controller'));
                    else
                        $currentRoutePermission = $routeName;
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
                    if (!in_array($currentRoutePermission, $currentUser->getPermissions())) {
                        if ($throwable)
                            throw new AccessDeniedHttpException("No permission to access this page");
                        else
                            return false;
                    }
                }
            }
        }
        return true;
    }
}