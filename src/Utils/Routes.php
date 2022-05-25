<?php

namespace App\Utils;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Routes
{
    /**
     * Extract permission name from a  Route object
     * @param $routeObj Route
     * @return string permission's name in lower case
     */
    public static function getPermissionName(Route | string $routeObj): string {
        if (gettype($routeObj) === 'object' && $routeObj instanceof Route) {
            $routeObj = $routeObj->getDefault('_controller');
        }
        $permissionName = explode('\\', $routeObj);
        $permissionName = end($permissionName);
        $permissionName = str_replace("Controller", "", $permissionName);
        $permissionName = str_replace("::", ":", $permissionName);
        return strtolower($permissionName);
    }

    /**
     * @param $collection
     * @return array
     */
    public  static function cleanRouteCollection($collection): array
    {
        return array_filter((array)$collection, function ($route) {
            return str_starts_with($route->getDefault('_controller'), 'App\Controller');
        });
    }

    /**
     * Determine if the permission is available on the application or not
     * @param array $routesCollection
     * @param string $permissionName
     * @return bool
     */
    public static function isPermissionPresent(array $routesCollection, string $permissionName): bool
    {
        foreach ($routesCollection as $route) {
            if (self::getPermissionName($route) == $permissionName) {
                return true;
            }
        }
        return false;
    }
}