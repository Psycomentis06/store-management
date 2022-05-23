<?php

namespace App\Service\Search;

use App\Utils\Str;
use Symfony\Component\Routing\RouterInterface;

class RouteSearchService
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function findAllByNameLike(string $name = ""): array
    {
        $routes = $this->router->getRouteCollection()->all();
        $res = [];
            foreach ($routes as $routeName => $route) {
                if (str_starts_with($routeName, 'app_')) {
                    $routeDesc = $route->getDefault('description');
                    if (!empty($routeDesc) && preg_match("/\b($name)\b/i", $routeDesc))
                    $res[] = [
                        "path" => $route->getPath(),
                        "description" => $route->getDefault('description')
                    ];
                    else if (preg_match("/($name)/i", $routeName))
                        $res[] = [
                            "path" => $route->getPath(),
                            "description" => $route->getDefault('description')
                        ];
                }

            }
            return $res;
    }

    public function getEntityIndexRoutePath(string $entityName):?string
    {
        $routeName = 'app_' . Str::capitalToUnderscore($entityName) . '_index';
        return $this->router->getRouteCollection()->get($routeName)->getPath();
    }
}