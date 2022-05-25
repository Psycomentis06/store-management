<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\Routes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class PermissionService
{
    private Security $security;
    private RouterInterface $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function checkPermission(Request $request, bool $throwable = false): bool
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
                    if (in_array($currentRoutePermission, $currentUser->getPermissions())) {
                        return true;
                    } else {
                        if ($throwable)
                            throw new AccessDeniedHttpException("No permission to access this page");
                        else
                            return false;
                    }
                }
            }
        }
        return false;
    }

    public function checkPermissionByRouteName(string $routeName): bool
    {
        $route = $this->router->getRouteCollection()->get($routeName);
        if (empty($route))
            return false;
        $routeRole = $route->getDefault('role');
        if (empty($routeRole) || strlen($routeRole) === 0)
            return true; // Empty role means route doesn't require permissions

        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY'))
            return false; // Route require permission && login as well

        $currentUser = $this->security->getUser();
        if ($currentUser instanceof User) {
            $permissions = $currentUser->getPermissions();
            $routePermission = Routes::getPermissionName($route);
            return in_array($routePermission, $permissions);
        }

        return false;
    }
}